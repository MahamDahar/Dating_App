@extends('layouts.user')
@section('usercontent')
<div class="page-content-wrapper">
    <div class="page-content">
        <div class="card border-0 shadow-sm rounded-4 mx-auto" style="max-width:720px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h5 class="mb-0 fw-semibold">{{ $callType === 'video' ? 'Video' : 'Voice' }} call</h5>
                        <small class="text-muted">With {{ $peer->name }}</small>
                    </div>
                    <a href="{{ route('user.chat.with', $peer->id) }}" class="btn btn-outline-secondary btn-sm">Back to chat</a>
                </div>

                <div class="alert alert-light border small mb-3">
                    <strong>How it works:</strong> Person starting the call opens this page as <em>caller</em>. The other person opens the same chat, taps <strong>Answer / Join</strong> (answer mode) with the same call type (voice or video). Both must allow camera/mic for video calls.
                </div>

                <div id="callStatus" class="text-muted small mb-2">Preparing…</div>

                <div class="ratio ratio-16x9 bg-dark rounded-3 mb-3 overflow-hidden" style="max-height:360px;">
                    <video id="remoteVideo" playsinline autoplay style="width:100%;height:100%;object-fit:cover;display:none;"></video>
                </div>
                <video id="localVideo" playsinline muted autoplay style="width:140px;height:100px;object-fit:cover;border-radius:12px;display:none;"></video>

                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-danger" id="btnHangup">End call</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const MY_ID     = {{ auth()->id() }};
    const PEER_ID   = {{ $peer->id }};
    const ROLE      = @json($callRole);
    const CALL_TYPE = @json($callType);
    const CSRF      = '{{ csrf_token() }}';
    const POST_URL  = @json(route('user.chat.signals.store', $peer->id));
    const POLL_URL  = @json(route('user.chat.signals.poll', $peer->id));

    const statusEl = document.getElementById('callStatus');
    const localV   = document.getElementById('localVideo');
    const remoteV  = document.getElementById('remoteVideo');

    let pc = null;
    let localStream = null;
    let lastSignalId = 0;
    let pollTimer = null;
    const iceCandidates = [];

    function setStatus(t) { statusEl.textContent = t; }

    async function postSignal(kind, payload) {
        await fetch(POST_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ kind, payload })
        });
    }

    async function flushIce() {
        while (iceCandidates.length && pc && pc.remoteDescription) {
            const c = iceCandidates.shift();
            try { await pc.addIceCandidate(c); } catch (e) { console.warn(e); }
        }
    }

    async function handleSignal(s) {
        if (parseInt(s.from_user_id, 10) === MY_ID) return;
        const desc = JSON.parse(s.payload);
        if (s.kind === 'offer' && ROLE === 'answer') {
            setStatus('Incoming call… connecting');
            await pc.setRemoteDescription(desc);
            await flushIce();
            const answer = await pc.createAnswer();
            await pc.setLocalDescription(answer);
            await postSignal('answer', JSON.stringify(answer));
            return;
        }
        if (s.kind === 'answer' && ROLE === 'caller') {
            setStatus('Connected');
            await pc.setRemoteDescription(desc);
            await flushIce();
            return;
        }
        if (s.kind === 'candidate') {
            const cand = new RTCIceCandidate(desc);
            if (!pc || !pc.remoteDescription) {
                iceCandidates.push(cand);
            } else {
                try { await pc.addIceCandidate(cand); } catch (e) { console.warn(e); }
            }
        }
    }

    async function poll() {
        try {
            const r = await fetch(POLL_URL + '?since=' + lastSignalId);
            const j = await r.json();
            for (const s of j.signals || []) {
                lastSignalId = Math.max(lastSignalId, s.id);
                await handleSignal(s);
            }
        } catch (e) { console.warn(e); }
    }

    async function start() {
        setStatus('Requesting camera / microphone…');
        localStream = await navigator.mediaDevices.getUserMedia({
            audio: true,
            video: CALL_TYPE === 'video'
        });
        if (CALL_TYPE === 'video') {
            localV.srcObject = localStream;
            localV.style.display = 'block';
        }

        pc = new RTCPeerConnection({
            iceServers: [{ urls: 'stun:stun.l.google.com:19302' }]
        });

        localStream.getTracks().forEach(t => pc.addTrack(t, localStream));

        pc.ontrack = ev => {
            remoteV.srcObject = ev.streams[0];
            remoteV.style.display = 'block';
            setStatus('In call');
        };

        pc.onicecandidate = ev => {
            if (ev.candidate) {
                postSignal('candidate', JSON.stringify(ev.candidate.toJSON()));
            }
        };

        pollTimer = setInterval(poll, 1200);
        poll();

        if (ROLE === 'caller') {
            setStatus('Calling…');
            const offer = await pc.createOffer();
            await pc.setLocalDescription(offer);
            await postSignal('offer', JSON.stringify(offer));
        } else {
            setStatus('Waiting for caller (open page as caller on other side first)…');
        }
    }

    document.getElementById('btnHangup').addEventListener('click', () => {
        if (pollTimer) clearInterval(pollTimer);
        if (localStream) localStream.getTracks().forEach(t => t.stop());
        if (pc) pc.close();
        window.location.href = @json(route('user.chat.with', $peer->id));
    });

    start().catch(e => {
        console.error(e);
        setStatus('Failed: ' + (e.message || 'Cannot start call'));
    });
})();
</script>
@endsection
