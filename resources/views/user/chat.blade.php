@extends('layouts.user')
@section('usercontent')
<div class="page-content-wrapper">
    <div class="page-content wa-chat-page dm-chat-theme">
        <style>
            :root {
                --wa-bg: #fff3f7;
                --wa-panel: #fff8fb;
                --wa-green: #e11d74;
                --wa-dark: #3b1f31;
                --wa-muted: #896378;
                --wa-bubble-in: #ffffff;
                --wa-bubble-out: #ffd6e8;
                --wa-border: #f3d3e2;
                /* Stars + tiny hearts (URL-encoded SVG tiles) */
                --wa-wallpaper-stars: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cg%3E%3Cpath fill='none' stroke='%23e8a4b8' stroke-width='0.42' stroke-linejoin='round' opacity='0.22' d='M50 10 L58.5 36.5 L86 36.5 L63.5 52 L71.5 86 L50 70.5 L28.5 86 L36.5 52 L14 36.5 L41.5 36.5 Z'/%3E%3Cpath fill='none' stroke='%23f48fb1' stroke-width='0.32' stroke-linejoin='round' opacity='0.18' d='M50 26 L54.5 42 L71 42 L57.5 51 L62 67.5 L50 58.5 L38 67.5 L42.5 51 L29 42 L45.5 42 Z'/%3E%3Ccircle cx='22' cy='24' r='2' fill='%23f8bbd0' opacity='0.45'/%3E%3Ccircle cx='80' cy='70' r='1.6' fill='%23f48fb1' opacity='0.35'/%3E%3Ccircle cx='84' cy='26' r='1.4' fill='%23e1bee7' opacity='0.4'/%3E%3C/g%3E%3C/svg%3E");
                --wa-wallpaper-hearts: url("data:image/svg+xml,%3Csvg width='72' height='72' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath fill='none' stroke='%23f48fb1' stroke-width='0.4' stroke-linejoin='round' opacity='0.14' d='M36 56 C20 44 14 34 14 26 C14 18 20 14 27 14 C32 14 35 17 36 20 C37 17 40 14 45 14 C52 14 58 18 58 26 C58 34 52 44 36 56Z'/%3E%3C/svg%3E");
            }
            .wa-chat-page {
                background:
                    radial-gradient(circle at 15% 10%, rgba(236,72,153,.12), transparent 40%),
                    radial-gradient(circle at 88% 85%, rgba(168,85,247,.10), transparent 35%),
                    var(--wa-bg);
                min-height: calc(100vh - 70px);
                padding: 14px 10px 8px;
                text-align: left;
            }
            .wa-wrap {
                display: flex;
                height: calc(100vh - 90px);
                max-width: 1680px;
                width: 100%;
                margin: 0 auto;
                background: #fff;
                border-radius: 22px;
                border: 1px solid #f1deea;
                box-shadow: 0 18px 42px rgba(77, 23, 53, 0.12);
                overflow: hidden;
                text-align: left;
            }
            .wa-chat-stage {
                flex: 1;
                display: flex;
                min-width: 0;
                align-items: stretch;
            }
            /* WhatsApp Web–style narrow rail (pink accent) */
            .wa-rail {
                width: 72px;
                min-width: 72px;
                background: linear-gradient(180deg, #fff7fb 0%, #fff2f8 100%);
                border-right: 1px solid var(--wa-border);
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 8px 0 12px;
                gap: 4px;
            }
            .wa-rail a, .wa-rail .wa-rail-btn {
                width: 52px;
                height: 52px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #54656f;
                text-decoration: none;
                border: none;
                background: transparent;
                cursor: pointer;
                position: relative;
                font-size: 22px;
            }
            .wa-rail a:hover, .wa-rail .wa-rail-btn:hover { background: rgba(0,0,0,.05); }
            .wa-rail a.is-active {
                background: rgba(236, 64, 122, 0.12);
                color: #c2185b;
            }
            .wa-rail-badge {
                position: absolute;
                top: 4px;
                right: 4px;
                min-width: 18px;
                height: 18px;
                padding: 0 4px;
                border-radius: 999px;
                background: linear-gradient(145deg, #f48fb1, #ec407a);
                color: #fff;
                font-size: 10px;
                font-weight: 700;
                display: flex;
                align-items: center;
                justify-content: center;
                line-height: 1;
            }
            .wa-rail-spacer { flex: 1; min-height: 8px; }
            .wa-rail-icon.wa-ghost {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                font-size: 22px;
                line-height: 1;
                color: #54656f;
            }
            .wa-inbox-top-actions .wa-rail-icon.wa-ghost {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
            }
            .wa-inbox-top-actions a.wa-rail-icon.wa-ghost {
                font-size: 30px;
                font-weight: 300;
                line-height: 0.9;
                padding-bottom: 2px;
            }
            .wa-inbox-dd { right: 0; left: auto; }
            .wa-chat-filters {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                padding: 8px 12px 10px;
                background: #fff;
                border-bottom: 1px solid var(--wa-border);
            }
            .wa-filter-pill {
                border: none;
                border-radius: 999px;
                padding: 6px 14px;
                font-size: 13px;
                font-weight: 500;
                cursor: pointer;
                background: #fff;
                color: #54656f;
                box-shadow: 0 0 0 1px #e9edef;
                font-family: inherit;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }
            .wa-filter-pill:hover { background: #f5f6f6; }
            .wa-filter-pill.is-on {
                background: linear-gradient(145deg, #fce4ec, #f8bbd0);
                color: #880e4f;
                box-shadow: 0 0 0 1px rgba(236, 64, 122, 0.35);
            }
            .wa-filter-count {
                background: linear-gradient(145deg, #f48fb1, #ec407a);
                color: #fff;
                border-radius: 999px;
                padding: 1px 7px;
                font-size: 11px;
                font-weight: 700;
            }
            .wa-user-row {
                display: flex;
                align-items: stretch;
                position: relative;
            }
            .wa-fav-btn {
                width: 40px;
                flex-shrink: 0;
                border: none;
                background: transparent;
                color: #b0b0b0;
                cursor: pointer;
                font-size: 18px;
                padding: 0;
                align-self: center;
            }
            .wa-fav-btn.is-on { color: #ec407a; }
            .wa-fav-btn:hover { color: #f48fb1; }
            .wa-inbox-search-wrap {
                padding: 8px 12px 10px;
                border-bottom: 1px solid var(--wa-border);
                background: var(--wa-panel);
            }
            .wa-inbox-search {
                width: 100%;
                border: none;
                border-radius: 10px;
                padding: 9px 12px 9px 36px;
                font-size: 14px;
                background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%239a7b87' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242.656a5 5 0 1 1 0-10 5 5 0 0 1 0 10z'/%3E%3C/svg%3E") 10px center no-repeat;
                box-shadow: inset 0 0 0 1px var(--wa-border);
                outline: none;
            }
            .wa-inbox-search:focus {
                box-shadow: inset 0 0 0 1px rgba(236, 64, 122, 0.45);
            }
            .wa-user-row-meta {
                flex: 1;
                min-width: 0;
                display: flex;
                flex-direction: column;
                gap: 2px;
            }
            .wa-user-row-top {
                display: flex;
                align-items: baseline;
                justify-content: space-between;
                gap: 8px;
            }
            .wa-user-row-name { font-weight: 600; font-size: 15px; color: var(--wa-dark); }
            .wa-user-row-time { font-size: 12px; color: var(--wa-muted); flex-shrink: 0; }
            .wa-user-row-preview {
                font-size: 13px;
                color: var(--wa-muted);
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .wa-unread-badge {
                min-width: 20px;
                height: 20px;
                padding: 0 6px;
                border-radius: 999px;
                background: linear-gradient(145deg, #f48fb1, #ec407a);
                color: #fff;
                font-size: 11px;
                font-weight: 700;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            }
            .wa-back-mob {
                display: none;
                width: 40px;
                height: 40px;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                color: var(--wa-dark);
                text-decoration: none;
                font-size: 22px;
                line-height: 1;
                flex-shrink: 0;
            }
            .wa-back-mob:hover { background: rgba(0,0,0,.06); }
            .wa-day-sep {
                text-align: center;
                margin: 14px 0 10px;
            }
            .wa-day-sep span {
                display: inline-block;
                padding: 5px 12px 6px;
                border-radius: 8px;
                font-size: 12.5px;
                font-weight: 600;
                color: #6d5a63;
                background: rgba(255, 255, 255, 0.92);
                box-shadow: 0 1px 3px rgba(233, 30, 140, 0.08);
            }
            .wa-inbox {
                width: 400px;
                min-width: 300px;
                max-width: 100%;
                border-right: 1px solid var(--wa-border);
                display: flex;
                flex-direction: column;
                background: linear-gradient(180deg, #fffdfd 0%, #fff8fb 100%);
            }
            .wa-inbox-top {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 8px;
                padding: 10px 16px;
                background: linear-gradient(180deg, #fff8fb 0%, #fff2f8 100%);
                border-bottom: 1px solid var(--wa-border);
            }
            .wa-inbox-title {
                font-weight: 600;
                font-size: 1.35rem;
                color: #3b4a54;
                letter-spacing: -0.02em;
            }
            .wa-inbox-top-actions { display: flex; align-items: center; gap: 4px; }
            .wa-user-list { flex: 1; overflow-y: auto; }
            .wa-user-row > a {
                flex: 1;
                min-width: 0;
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 8px 12px 16px;
                color: var(--wa-dark);
                text-decoration: none;
                border-bottom: 1px solid #e9edef;
                transition: background .15s;
            }
            .wa-user-row > a:hover { background: #fff3f9; }
            .wa-user-row.active > a {
                background: linear-gradient(90deg, #ffe8f3 0%, #fff4fa 100%);
                box-shadow: inset 3px 0 0 #ec407a;
            }
            .wa-avatar {
                width: 48px; height: 48px; border-radius: 50%;
                background: linear-gradient(145deg, #f8bbd0 0%, #f48fb1 45%, #ce93d8 100%);
                color: #fff; display: flex; align-items: center; justify-content: center;
                font-weight: 700; font-size: 16px; flex-shrink: 0;
                box-shadow: 0 2px 8px rgba(244, 143, 177, 0.35);
            }
            .wa-avatar--sm {
                width: 32px;
                height: 32px;
                min-width: 32px;
                font-size: 12px;
                border-radius: 50%;
            }
            .wa-main {
                flex: 1;
                display: flex;
                flex-direction: column;
                min-width: 0;
                background: #fff7fb;
                text-align: left;
            }
            .wa-main-empty, .wa-intro {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                padding: 32px 24px;
                background: #f0f2f5;
                border-left: 1px solid #e9edef;
            }
            .wa-intro-visual {
                width: 320px;
                max-width: 90%;
                height: 200px;
                margin-bottom: 28px;
                opacity: 0.85;
                background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 320 200'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0%25' y1='0%25' x2='100%25' y2='100%25'%3E%3Cstop offset='0%25' stop-color='%23f8bbd0'/%3E%3Cstop offset='100%25' stop-color='%23ce93d8'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect fill='%23f5f6f6' width='320' height='200' rx='12'/%3E%3Cpath fill='url(%23g)' opacity='0.35' d='M160 40c44 0 80 36 80 80s-36 80-80 80-80-36-80-80 36-80 80-80zm0 28a52 52 0 1 0 0 104 52 52 0 0 0 0-104z'/%3E%3Cpath fill='%23ec407a' opacity='0.5' d='M130 95h60v10h-60zm0 22h40v10h-40z'/%3E%3C/svg%3E") center/contain no-repeat;
            }
            .wa-intro h2 {
                font-weight: 300;
                font-size: 2rem;
                color: #41525d;
                margin: 0 0 12px;
                letter-spacing: -0.03em;
            }
            .wa-intro p {
                max-width: 420px;
                color: #667781;
                font-size: 14px;
                line-height: 1.5;
                margin: 0;
            }
            .wa-topbar {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 10px 16px;
                background: linear-gradient(180deg, #fff8fb 0%, #fff0f7 100%);
                border-bottom: 1px solid var(--wa-border);
                position: relative;
                z-index: 20;
                text-align: left;
            }
            .wa-msg-search-row {
                display: none;
                padding: 8px 16px;
                background: #fff4fa;
                border-bottom: 1px solid var(--wa-border);
            }
            .wa-msg-search-row.is-on { display: block; }
            .wa-msg-search-row input {
                width: 100%;
                border: none;
                border-radius: 8px;
                padding: 10px 14px;
                font-size: 14px;
                background: #fff;
                box-shadow: inset 0 0 0 1px #e9edef;
                outline: none;
            }
            .wa-topbar-name { font-weight: 600; color: var(--wa-dark); flex: 1; min-width: 0; }
            .wa-topbar-actions { display: flex; gap: 4px; align-items: center; flex-shrink: 0; }
            .wa-menu-wrap { position: relative; }
            .wa-dropdown {
                display: none;
                position: absolute;
                right: 0;
                top: calc(100% + 4px);
                min-width: 240px;
                padding: 6px 0;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 8px 28px rgba(74, 53, 64, 0.18);
                border: 1px solid var(--wa-border);
                z-index: 100;
            }
            .wa-menu-wrap.is-open .wa-dropdown { display: block; }
            .wa-menu-item {
                display: block;
                width: 100%;
                text-align: left;
                padding: 12px 18px;
                border: none;
                background: none;
                font-size: 15px;
                color: var(--wa-dark);
                cursor: pointer;
                font-family: inherit;
            }
            .wa-menu-item:hover { background: #fff5f9; }
            .wa-menu-item:active { background: #ffe4ef; }
            .wa-menu-item .mi {
                display: inline-flex;
                width: 18px;
                justify-content: center;
                margin-right: 10px;
                opacity: 0.85;
            }
            .wa-menu-sep {
                margin: 6px 0;
                border-top: 1px solid #f2dce6;
            }
            .wa-menu-item.danger {
                color: #b42334;
            }
            .wa-menu-item.danger:hover {
                background: #fff0f3;
            }
            .wa-msg-menu {
                position: fixed;
                z-index: 220;
                min-width: 220px;
                background: #fff;
                border: 1px solid #f1dbe6;
                border-radius: 14px;
                box-shadow: 0 14px 36px rgba(40, 30, 35, 0.22);
                display: none;
                overflow: hidden;
            }
            .wa-msg-menu.is-on { display: block; }
            .wa-msg-reacts {
                display: flex;
                gap: 4px;
                align-items: center;
                padding: 8px 10px;
                border-bottom: 1px solid #f7e8ef;
                background: #fffdfd;
            }
            .wa-msg-reacts button {
                width: 28px;
                height: 28px;
                border: none;
                border-radius: 999px;
                background: #fff5f9;
                cursor: pointer;
                font-size: 16px;
                line-height: 1;
            }
            .wa-msg-reacts button:hover { background: #ffe6f0; }
            .wa-msg-act {
                display: block;
                width: 100%;
                text-align: left;
                border: none;
                background: #fff;
                padding: 10px 14px;
                font-size: 14px;
                color: #43323a;
                cursor: pointer;
                font-family: inherit;
            }
            .wa-msg-act:hover { background: #fff5f9; }
            .wa-msg-act + .wa-msg-act { border-top: 1px solid #f8ebf1; }
            .wa-msg-act .mi {
                display: inline-flex;
                width: 18px;
                justify-content: center;
                margin-right: 10px;
                opacity: .85;
            }
            .wa-star-icon {
                color: #ffb300;
                font-size: 12px;
                margin-right: 2px;
                line-height: 1;
            }
            .wa-modal {
                position: fixed;
                inset: 0;
                z-index: 200;
                display: none;
                align-items: center;
                justify-content: center;
                padding: 16px;
            }
            .wa-modal.is-on { display: flex; }
            .wa-modal-backdrop {
                position: absolute;
                inset: 0;
                background: rgba(40, 30, 35, 0.45);
            }
            .wa-modal-box {
                position: relative;
                width: 100%;
                max-width: 420px;
                max-height: 85vh;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                background: #fff;
                border-radius: 16px;
                box-shadow: 0 16px 48px rgba(0,0,0,.2);
            }
            .wa-modal-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 14px 16px;
                border-bottom: 1px solid var(--wa-border);
                font-weight: 700;
                font-size: 17px;
                color: var(--wa-dark);
            }
            .wa-modal-body {
                padding: 12px 16px 16px;
                overflow-y: auto;
            }
            .wa-modal-close {
                border: none;
                background: none;
                font-size: 22px;
                line-height: 1;
                cursor: pointer;
                color: var(--wa-muted);
                padding: 4px 8px;
            }
            .wa-starred-row {
                padding: 12px 0;
                border-bottom: 1px solid #fdeef4;
                font-size: 14px;
            }
            .wa-starred-row:last-child { border-bottom: none; }
            .wa-starred-meta { font-size: 11px; color: var(--wa-muted); margin-bottom: 4px; }
            .wa-sett-link {
                display: block;
                padding: 14px 4px;
                color: var(--wa-dark);
                text-decoration: none;
                border-bottom: 1px solid #fdeef4;
                font-size: 15px;
            }
            .wa-sett-link:last-child { border-bottom: none; }
            .wa-sett-link:hover { color: #ec407a; }
            .wa-toast-mini {
                position: fixed;
                bottom: 90px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 150;
                background: rgba(60, 45, 52, 0.92);
                color: #fff;
                padding: 10px 18px;
                border-radius: 999px;
                font-size: 14px;
                opacity: 0;
                pointer-events: none;
                transition: opacity .2s;
            }
            .wa-toast-mini.is-on { opacity: 1; }
            .wa-icon-btn {
                width: 40px; height: 40px; border: none; border-radius: 50%;
                background: transparent; color: #54656f;
                display: flex; align-items: center; justify-content: center;
                cursor: pointer; text-decoration: none;
                font-size: 18px;
            }
            .wa-icon-btn:hover { background: #ffe9f4; color: #cf2f79; }
            .wa-messages {
                flex: 1;
                overflow-y: auto;
                padding: 10px 14px 16px;
                text-align: left;
                background-color: #fff3f8;
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' stroke='%23c9b8c0' stroke-width='0.35' opacity='0.35'%3E%3Cpath d='M8 12h6v6H8zM44 8h8v8h-8zM22 38h5v5h-5zM48 42h4v4h-4zM12 48h3v3h-3z'/%3E%3Ccircle cx='30' cy='18' r='2'/%3E%3Ccircle cx='15' cy='28' r='1.5'/%3E%3Ccircle cx='50' cy='22' r='1.5'/%3E%3C/g%3E%3C/svg%3E");
                background-size: 60px 60px;
            }
            .wa-bubble-wrap {
                display: flex;
                margin-bottom: 6px;
            }
            .wa-bubble-wrap.in {
                flex-direction: row;
                align-items: flex-end;
                gap: 8px;
            }
            .wa-bubble-wrap.out { justify-content: flex-end; }
            .wa-bubble-cluster {
                display: flex;
                flex-direction: row;
                align-items: flex-start;
                gap: 8px;
                min-width: 0;
                max-width: 100%;
            }
            .wa-bubble-wrap.out .wa-bubble-cluster {
                flex-direction: row-reverse;
            }
            .wa-bubble {
                position: relative;
                max-width: 68%;
                padding: 9px 12px 8px 12px;
                border-radius: 18px 18px 18px 6px;
                box-shadow: 0 4px 14px rgba(173, 46, 108, 0.10);
                font-size: 14.2px;
                line-height: 1.45;
                color: #3a2330;
                text-align: left;
                word-wrap: break-word;
            }
            .wa-bubble-wrap.in .wa-bubble::after {
                content: '';
                position: absolute;
                left: -5px;
                bottom: 3px;
                width: 0;
                height: 0;
                border-style: solid;
                border-width: 0 6px 8px 0;
                border-color: transparent #fff transparent transparent;
                filter: drop-shadow(-1px 1px 0 rgba(245, 208, 222, 0.65));
            }
            .wa-bubble-wrap.out .wa-bubble {
                border-radius: 18px 18px 6px 18px;
            }
            .wa-bubble-wrap.out .wa-bubble::after {
                content: '';
                position: absolute;
                right: -5px;
                bottom: 3px;
                width: 0;
                height: 0;
                border-style: solid;
                border-width: 0 0 8px 6px;
                border-color: transparent transparent transparent #f5d0e0;
                filter: drop-shadow(1px 1px 0 rgba(248, 187, 208, 0.55));
            }
            .wa-bubble.in {
                background: #fff;
                border: 1px solid #f0d9e6;
                box-shadow: 0 3px 8px rgba(154, 74, 120, 0.08);
            }
            .wa-bubble.out {
                background: linear-gradient(145deg, #ffd9eb 0%, #ffcbe3 100%);
                border: 1px solid #f0b8d3;
                box-shadow: 0 4px 10px rgba(194, 62, 124, 0.14);
            }
            .wa-bubble .chat-img {
                display: block;
                max-width: 280px;
                max-height: 280px;
                border-radius: 12px;
                margin: 2px 0;
            }
            .wa-bubble-footer {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 4px;
                margin-top: 2px;
                font-size: 11px;
                color: var(--wa-muted);
            }
            .wa-bubble-wrap.in .wa-bubble-footer { justify-content: flex-start; }
            .ticks {
                display: inline-flex;
                align-items: center;
                margin-left: 2px;
                vertical-align: middle;
            }
            .ticks.sent { color: var(--wa-muted); }
            .ticks.read { color: #ec407a; }
            .ticks svg { display: block; }
            .wa-composer {
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 0;
                padding: 10px 16px 12px;
                background: linear-gradient(180deg, #fff8fb 0%, #fff1f8 100%);
                border-top: none;
            }
            .wa-composer-line {
                display: flex;
                align-items: center;
                gap: 6px;
                width: 100%;
            }
            .wa-composer-inner-wrap {
                flex: 1;
                min-width: 0;
                display: flex;
                flex-direction: column;
            }
            .wa-composer-inner {
                flex: 1;
                display: flex;
                align-items: center;
                gap: 6px;
                background: #fff;
                border-radius: 26px;
                padding: 6px 9px 6px 12px;
                min-height: 48px;
                box-sizing: border-box;
                border: 1px solid #f2d7e6;
                box-shadow: 0 4px 12px rgba(144, 48, 100, 0.08);
            }
            .wa-composer-inner input[type="text"] {
                flex: 1;
                border: none;
                outline: none;
                font-size: 15px;
                background: transparent;
                min-width: 0;
                text-align: left;
            }
            .wa-attach-plus {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                color: #54656f;
                flex-shrink: 0;
                font-size: 34px;
                font-weight: 300;
                line-height: 0.85;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                transform: translateY(2px);
            }
            .wa-attach-plus:hover { background: rgba(0,0,0,.05); }
            .wa-emoji-pop {
                display: none;
                position: absolute;
                bottom: 100%;
                left: 0;
                right: 0;
                margin-bottom: 8px;
                padding: 10px;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0,0,0,.15);
                border: 1px solid #e9edef;
                flex-wrap: wrap;
                gap: 6px;
                max-height: 200px;
                overflow-y: auto;
                z-index: 50;
            }
            .wa-emoji-pop.is-on { display: flex; }
            .wa-emoji-pop button {
                border: none;
                background: #f5f6f6;
                width: 36px;
                height: 36px;
                border-radius: 8px;
                font-size: 20px;
                cursor: pointer;
                line-height: 1;
            }
            .wa-emoji-pop button:hover { background: #fce4ec; }
            .wa-composer-emoji-wrap { position: relative; flex-shrink: 0; }
            .wa-send-btn {
                width: 48px; height: 48px;
                border-radius: 50%;
                border: none;
                background: linear-gradient(145deg, #ec4899, #be185d);
                color: #fff;
                font-size: 20px;
                cursor: pointer;
                display: none;
                align-items: center;
                justify-content: center;
                box-shadow: 0 8px 16px rgba(190, 24, 93, 0.30);
                flex-shrink: 0;
            }
            .wa-send-btn:hover { filter: brightness(1.05); transform: translateY(-1px); }
            .wa-send-btn.is-visible { display: flex; }
            .wa-mic-send-slot {
                width: 48px;
                height: 48px;
                flex-shrink: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .wa-mic-send-slot .wa-icon-btn { margin: 0; width: 48px; height: 48px; font-size: 22px; }
            .wa-send-btn:disabled { opacity: .5; cursor: not-allowed; }
            .wa-toast-err {
                background: #fee2e2;
                color: #991b1b;
                padding: 8px 14px;
                margin: 8px 16px 0;
                border-radius: 8px;
                font-size: 13px;
            }
            .wa-rec-hint {
                font-size: 11px;
                color: #b91c1c;
                min-height: 16px;
                margin: 6px 0 0 0;
                padding: 0 54px 0 54px;
                text-align: left;
            }
            .wa-select-bar {
                display: none;
                align-items: center;
                flex-wrap: wrap;
                gap: 8px;
                padding: 8px 12px;
                background: #fff0f5;
                border-bottom: 1px solid var(--wa-border);
                font-size: 13px;
                color: var(--wa-dark);
            }
            .wa-main.wa-select-on .wa-select-bar { display: flex; }
            .wa-main.wa-select-on .wa-composer { display: none; }
            .wa-main.wa-select-on .wa-msg-check { display: flex; }
            .wa-msg-check {
                display: none;
                align-items: flex-start;
                padding-top: 8px;
                flex-shrink: 0;
            }
            .wa-msg-check input {
                width: 18px; height: 18px; cursor: pointer;
                accent-color: #ec407a;
            }
            .wa-main.wa-select-on .wa-bubble-wrap {
                cursor: pointer;
                align-items: flex-start;
                gap: 8px;
            }
            .wa-main.wa-select-on .wa-peer-avatar {
                display: none !important;
            }
            .wa-main.wa-select-on .wa-bubble-wrap.out .wa-bubble-cluster {
                flex-direction: row-reverse;
            }
            .wa-main.wa-select-on .wa-bubble-wrap.out { flex-direction: row-reverse; }
            .wa-btn-chat {
                border: none;
                border-radius: 999px;
                padding: 8px 14px;
                font-size: 13px;
                cursor: pointer;
                background: #fff;
                box-shadow: 0 0 0 1px var(--wa-border);
                color: var(--wa-dark);
            }
            .wa-btn-chat:hover { background: #fff5f9; }
            .wa-btn-chat.primary {
                background: linear-gradient(145deg, #f48fb1, #ec407a);
                color: #fff;
                box-shadow: none;
            }
            .wa-btn-chat:disabled { opacity: .45; cursor: not-allowed; }
            .wa-revoked {
                font-style: italic;
                opacity: .85;
                font-size: 13px;
                color: var(--wa-muted);
            }

            /* ---------- Modern Messenger/Instagram-style UI Overrides ---------- */
            .wa-wrap {
                border-radius: 28px;
                border: 1px solid #efd7e8;
                box-shadow: 0 24px 56px rgba(86, 23, 58, 0.15);
                background: #fff;
            }
            .wa-rail {
                width: 78px;
                min-width: 78px;
                background: linear-gradient(180deg, #fff9fd 0%, #fff3fa 100%);
                border-right: 1px solid #f1dceb;
                padding-top: 12px;
            }
            .wa-rail a, .wa-rail .wa-rail-btn {
                width: 54px;
                height: 54px;
                border-radius: 16px;
                color: #7c5a6b;
                transition: all .2s ease;
            }
            .wa-rail a:hover, .wa-rail .wa-rail-btn:hover {
                background: #ffeaf5;
                color: #c01f67;
                transform: translateY(-1px);
            }
            .wa-rail a.is-active {
                background: linear-gradient(145deg, #ff5ea8, #d946ef);
                color: #fff;
                box-shadow: 0 10px 20px rgba(217, 70, 239, 0.3);
            }

            .wa-inbox {
                background: linear-gradient(180deg, #fffefe 0%, #fff9fc 100%);
            }
            .wa-inbox-top {
                background: rgba(255, 255, 255, 0.84);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid #f4e4ef;
                padding: 12px 16px;
            }
            .wa-inbox-title {
                font-size: 1.2rem;
                font-weight: 700;
                color: #412437;
            }
            .wa-chat-filters {
                background: transparent;
                border-bottom: 1px solid #f7eaf2;
                padding: 8px 12px 12px;
            }
            .wa-filter-pill {
                background: #fff;
                box-shadow: 0 0 0 1px #f0dde9;
                color: #7c5a6b;
                font-weight: 600;
            }
            .wa-filter-pill.is-on {
                background: #ffe8f4;
                color: #b0165f;
                box-shadow: 0 0 0 1px #f3a9cc;
            }
            .wa-inbox-search-wrap {
                background: transparent;
                border-bottom: 1px solid #f7eaf2;
                padding-top: 6px;
            }
            .wa-inbox-search {
                border-radius: 14px;
                height: 42px;
                font-size: 13.5px;
                box-shadow: inset 0 0 0 1px #efd8e7, 0 4px 10px rgba(167, 57, 120, 0.06);
            }
            .wa-user-row > a {
                border-bottom: 1px solid #f8edf3;
                padding: 13px 10px 13px 16px;
            }
            .wa-user-row > a:hover {
                background: #fff1f8;
            }
            .wa-user-row.active > a {
                background: linear-gradient(90deg, #ffeaf5 0%, #fff7fc 100%);
                box-shadow: inset 3px 0 0 #ef4899;
            }
            .wa-user-row-name {
                color: #3f2434;
                font-weight: 700;
                letter-spacing: .1px;
            }
            .wa-user-row-preview {
                color: #926f82;
            }
            .wa-avatar {
                border: 2px solid #fff;
                box-shadow: 0 8px 14px rgba(220, 74, 146, 0.22);
            }

            .wa-main {
                background: #fff8fc;
            }
            .wa-topbar {
                background: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(8px);
                border-bottom: 1px solid #f1ddea;
                padding: 11px 16px;
            }
            .wa-topbar-name {
                font-weight: 700;
                color: #3f2536;
                font-size: 15.5px;
            }
            .wa-msg-search-row {
                background: #fff8fc;
                border-bottom: 1px solid #f1e0ea;
            }
            .wa-msg-search-row input {
                border-radius: 12px;
                box-shadow: inset 0 0 0 1px #edd8e6;
            }
            .wa-icon-btn {
                border-radius: 12px;
                width: 38px;
                height: 38px;
                color: #7d5a6d;
            }
            .wa-icon-btn:hover {
                background: #ffe9f4;
                color: #cb2d77;
            }

            .wa-messages {
                background-color: #fff3f9;
                background-image:
                    radial-gradient(circle at 20% 20%, rgba(255,255,255,.7) 0 2px, transparent 2px),
                    radial-gradient(circle at 80% 30%, rgba(255,255,255,.6) 0 1.5px, transparent 1.5px),
                    radial-gradient(circle at 40% 70%, rgba(248,187,208,.35) 0 2px, transparent 2px),
                    radial-gradient(circle at 70% 85%, rgba(216,180,254,.25) 0 2px, transparent 2px);
                background-size: 120px 120px, 90px 90px, 160px 160px, 140px 140px;
                padding: 14px 18px 18px;
            }
            .wa-day-sep span {
                background: rgba(255, 255, 255, 0.95);
                border: 1px solid #f1dce8;
                color: #7f5c70;
                box-shadow: 0 6px 14px rgba(166, 48, 113, 0.10);
            }
            .wa-bubble {
                max-width: min(70%, 520px);
                border-radius: 20px;
                padding: 10px 13px 8px;
                font-size: 14px;
                line-height: 1.48;
            }
            .wa-bubble.in {
                background: #fff;
                border: 1px solid #f0ddeb;
                box-shadow: 0 8px 16px rgba(93, 38, 67, 0.07);
            }
            .wa-bubble.out {
                background: linear-gradient(145deg, #ffd4ea 0%, #ffc4e1 100%);
                border: 1px solid #f1b6d4;
                box-shadow: 0 10px 18px rgba(199, 48, 122, 0.17);
            }
            .wa-bubble-wrap.in .wa-bubble::after,
            .wa-bubble-wrap.out .wa-bubble::after {
                display: none;
            }
            .wa-bubble .chat-img {
                border-radius: 14px;
                border: 1px solid #f3dbe9;
                box-shadow: 0 6px 14px rgba(136, 44, 93, 0.12);
            }
            .wa-bubble-footer {
                margin-top: 4px;
                font-size: 10.8px;
                color: #916c80;
            }
            .ticks.read { color: #db2777; }

            .wa-composer {
                background: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(6px);
                border-top: 1px solid #f0dde8;
                padding: 10px 14px 12px;
            }
            .wa-composer-inner {
                border: 1px solid #f0dbe8;
                border-radius: 28px;
                min-height: 50px;
                box-shadow: 0 8px 14px rgba(159, 56, 115, 0.10);
                background: #fff;
            }
            .wa-composer-inner input[type="text"] {
                font-size: 14px;
                color: #3d2534;
            }
            .wa-composer-inner input[type="text"]::placeholder {
                color: #a27c90;
            }
            .wa-attach-plus {
                width: 44px;
                height: 44px;
                font-size: 30px;
                border-radius: 50%;
                color: #bd2d73;
            }
            .wa-attach-plus:hover {
                background: #ffeaf4;
            }
            .wa-send-btn {
                width: 50px;
                height: 50px;
                background: linear-gradient(145deg, #ec4899, #a855f7);
                box-shadow: 0 10px 18px rgba(168, 85, 247, 0.32);
                border: 1px solid rgba(255,255,255,.4);
            }
            .wa-send-btn:hover {
                filter: brightness(1.05);
                transform: translateY(-1px);
            }
            .wa-mic-send-slot .wa-icon-btn {
                width: 44px;
                height: 44px;
                border-radius: 50%;
            }

            .wa-modal-backdrop {
                background: rgba(40, 14, 29, 0.56);
                backdrop-filter: blur(2px);
            }
            .wa-modal-box {
                border-radius: 20px;
                border: 1px solid #f1d9e8;
            }
            .wa-dropdown, .wa-msg-menu {
                border-radius: 14px;
                border: 1px solid #f1ddea;
                box-shadow: 0 16px 36px rgba(82, 27, 58, 0.18);
            }

            .wa-intro h2 {
                font-weight: 700;
                font-size: 2.1rem;
                color: #3e2435;
            }
            .wa-intro p {
                color: #8b6679;
                font-size: 14.5px;
            }

            /* ---------- Layout shift: non-WhatsApp modern DM ---------- */
            .wa-rail { display: none !important; }
            .wa-wrap {
                display: grid;
                grid-template-columns: 360px minmax(0, 1fr);
                gap: 14px;
                height: calc(100vh - 96px);
                max-width: 1500px;
                background: transparent;
                border: none;
                box-shadow: none;
                overflow: visible;
                border-radius: 0;
            }
            .wa-inbox, .wa-main {
                border-radius: 24px;
                border: 1px solid #efddeb;
                overflow: hidden;
                box-shadow: 0 16px 36px rgba(92, 29, 67, 0.12);
                background: #fff;
            }
            .wa-inbox {
                width: 100%;
                min-width: 0;
                max-width: none;
            }
            .wa-main {
                min-width: 0;
                position: relative;
            }

            .wa-inbox-top {
                padding: 14px 16px;
                background: #ffffff;
                backdrop-filter: none;
            }
            .wa-inbox-title {
                font-size: 1.08rem;
                letter-spacing: .2px;
            }
            .wa-inbox-top-actions {
                gap: 8px;
            }
            .wa-inbox-top-actions .wa-rail-icon.wa-ghost {
                width: 36px;
                height: 36px;
                border-radius: 10px;
                font-size: 17px;
                margin-top: 0 !important;
                background: #fff3f9;
                color: #b42369;
            }

            .wa-chat-filters {
                padding: 8px 12px 10px;
                gap: 6px;
            }
            .wa-filter-pill {
                border-radius: 12px;
                font-size: 12px;
                padding: 6px 11px;
                box-shadow: 0 0 0 1px #edd9e6;
            }
            .wa-inbox-search-wrap {
                padding: 8px 12px 12px;
            }

            .wa-user-list {
                padding: 8px;
                background: #fffcfe;
            }
            .wa-user-row {
                margin-bottom: 6px;
                border-radius: 16px;
                overflow: hidden;
            }
            .wa-user-row > a {
                border: 1px solid #f5e6ee;
                border-radius: 16px;
                padding: 12px 10px 12px 12px;
                background: #fff;
            }
            .wa-user-row > a:hover {
                background: #fff6fb;
                border-color: #efcde0;
            }
            .wa-user-row.active > a {
                background: linear-gradient(110deg, #ffe9f4 0%, #fff9fd 100%);
                border-color: #edbfd9;
                box-shadow: 0 10px 18px rgba(197, 73, 136, 0.12);
                inset: auto;
            }
            .wa-user-row.active > a::before { content: none; }
            .wa-avatar {
                width: 46px;
                height: 46px;
                border-radius: 14px;
            }
            .wa-user-row-name {
                font-size: 14px;
            }
            .wa-user-row-preview {
                font-size: 12.5px;
            }

            .wa-topbar {
                background: #fff;
                border-bottom: 1px solid #f3e4ec;
                padding: 12px 16px;
            }
            .wa-topbar .wa-avatar {
                border-radius: 12px;
            }
            .wa-topbar-name {
                font-size: 15px;
            }

            .wa-messages {
                padding: 18px 22px 90px;
                background-color: #fff7fc;
            }
            .wa-bubble {
                border-radius: 26px;
                max-width: min(72%, 560px);
                border: 1px solid transparent;
                overflow: visible;
            }
            .wa-bubble.in {
                border-radius: 26px 26px 26px 22px;
                background: #ffffff;
                border-color: #e6ddeb;
                color: #3a2330;
                clip-path: none;
            }
            .wa-bubble.out {
                border-radius: 26px 26px 22px 26px;
                background: linear-gradient(145deg, #ffd7ea 0%, #ffc9e2 100%);
                border-color: #efbed6;
                color: #3a2330;
                clip-path: none;
                box-shadow: 0 8px 16px rgba(184, 58, 120, 0.16);
            }
            .wa-bubble-wrap.in .wa-bubble::after,
            .wa-bubble-wrap.out .wa-bubble::after {
                display: block;
                content: '';
                position: absolute;
                bottom: 6px;
                width: 14px;
                height: 14px;
                transform: rotate(45deg);
                border-bottom-right-radius: 3px;
                z-index: 1;
            }
            .wa-bubble-wrap.in .wa-bubble::after {
                left: -5px;
                background: #ffffff;
                border-left: 1px solid #e6ddeb;
                border-bottom: 1px solid #e6ddeb;
            }
            .wa-bubble-wrap.out .wa-bubble::after {
                right: -5px;
                background: #ffcee5;
                border-right: 1px solid #efbed6;
                border-bottom: 1px solid #efbed6;
            }
            .wa-bubble-wrap.in .wa-bubble::after,
            .wa-bubble-wrap.out .wa-bubble::after {
                display: none;
            }
            .wa-bubble-wrap {
                margin-bottom: 10px;
            }

            .wa-composer {
                position: absolute;
                left: 14px;
                right: 14px;
                bottom: 12px;
                border: 1px solid #f1deea;
                border-radius: 18px;
                background: rgba(255,255,255,.96);
                box-shadow: 0 12px 24px rgba(109, 36, 78, 0.14);
                padding: 8px 10px;
                z-index: 30;
            }
            .wa-composer-inner {
                min-height: 46px;
                border-radius: 14px;
                box-shadow: none;
                border: 1px solid #f1dce9;
            }
            .wa-send-btn {
                width: 44px;
                height: 44px;
                border-radius: 14px;
            }
            .wa-mic-send-slot .wa-icon-btn {
                width: 40px;
                height: 40px;
                border-radius: 12px;
            }

            .wa-main-empty, .wa-intro {
                border-left: none;
                border-radius: 24px;
                background: linear-gradient(180deg, #fffafe 0%, #fff3f9 100%);
            }

            @@media (max-width: 900px) {
                .wa-rail { display: none; }
                .wa-wrap {
                    grid-template-columns: 320px minmax(0, 1fr);
                }
            }
            @@media (max-width: 768px) {
                .wa-inbox { width: 100%; max-width: none; min-width: 0; }
                .wa-wrap {
                    display: flex;
                    flex-direction: column;
                    height: auto;
                    min-height: calc(100vh - 70px);
                    border-radius: 0;
                    border: none;
                    box-shadow: none;
                    gap: 0;
                    padding: 0;
                    max-width: 100%;
                }
                .wa-inbox, .wa-main {
                    border-radius: 0;
                    border: none;
                    box-shadow: none;
                }
                .wa-composer {
                    position: static;
                    border-radius: 0;
                    border: none;
                    box-shadow: none;
                }
                .wa-messages { padding: 14px 14px 16px; }
                .wa-main { min-height: 400px; }
                .wa-wrap.wa-has-active .wa-inbox { display: none; }
                .wa-wrap.wa-has-active .wa-chat-stage {
                    display: flex;
                    width: 100%;
                    min-height: calc(100vh - 140px);
                }
                .wa-wrap:not(.wa-has-active) .wa-chat-stage { display: none; }
                .wa-back-mob { display: inline-flex; }
            }

            /* ===== Reference: modern dating DM (Messenger/Insta-style) ===== */
            .dm-chat-theme.wa-chat-page {
                background: linear-gradient(165deg, #fff5f9 0%, #faf5ff 45%, #fff8fc 100%);
                padding: 12px 14px 16px;
            }
            .dm-chat-theme .wa-rail { display: none !important; }
            .dm-chat-theme .wa-wrap {
                display: grid;
                grid-template-columns: minmax(280px, 360px) minmax(0, 1fr);
                gap: 18px;
                max-width: 1320px;
                margin: 0 auto;
                background: transparent;
                border: none;
                box-shadow: none;
                height: calc(100vh - 88px);
                overflow: visible;
            }
            .dm-chat-theme .wa-chat-stage {
                display: flex;
                flex-direction: row;
                align-items: stretch;
                gap: 18px;
                min-width: 0;
                min-height: 0;
            }
            .dm-chat-theme .wa-chat-stage .wa-main {
                flex: 1;
                min-width: 0;
            }
            .dm-chat-theme .wa-inbox,
            .dm-chat-theme .wa-main {
                border-radius: 28px;
                border: 1px solid rgba(244, 211, 232, 0.95);
                box-shadow: 0 20px 50px rgba(192, 70, 130, 0.12);
                background: rgba(255, 255, 255, 0.92);
                backdrop-filter: blur(12px);
            }
            .dm-chat-theme .wa-main {
                background: linear-gradient(180deg, #fff4fa 0%, #fdf0ff 100%);
            }
            .dm-chat-theme .wa-inbox-top.dm-inbox-header {
                display: grid;
                grid-template-columns: 44px 1fr auto;
                align-items: center;
                gap: 8px;
                padding: 14px 14px 10px;
                background: transparent;
                border-bottom: 1px solid #fce7f3;
            }
            .dm-chat-theme .dm-inbox-title-center {
                margin: 0;
                text-align: center;
                font-size: 1.15rem;
                font-weight: 800;
                letter-spacing: -0.02em;
                color: #4a1d3a;
            }
            .dm-chat-theme .dm-head-circle-btn,
            .dm-chat-theme .dm-head-plus {
                width: 44px;
                height: 44px;
                border-radius: 50%;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                color: #9d174d;
                background: #fff;
                border: none;
                box-shadow: 0 6px 16px rgba(236, 72, 153, 0.15);
                font-size: 22px;
                font-weight: 300;
                line-height: 1;
                cursor: pointer;
                transition: transform .15s ease, box-shadow .15s ease;
            }
            .dm-chat-theme .dm-head-circle-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 18px rgba(236, 72, 153, 0.22);
            }
            .dm-chat-theme .wa-inbox-top-actions {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .dm-chat-theme .wa-inbox-search-wrap {
                padding: 6px 14px 10px;
                border-bottom: 1px solid #fdf2f8;
            }
            .dm-chat-theme .dm-search-pill {
                border-radius: 999px;
                height: 46px;
                padding-left: 44px;
                font-size: 14px;
                background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' fill='%23c084fc' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85zm-5.242.656a5 5 0 1 1 0-10 5 5 0 0 1 0 10z'/%3E%3C/svg%3E") 14px center no-repeat;
                box-shadow: 0 4px 14px rgba(244, 114, 182, 0.12);
                border: 1px solid #fce7f3;
            }
            .dm-chat-theme .wa-active-strip {
                display: flex;
                gap: 14px;
                padding: 12px 14px 4px;
                overflow-x: auto;
                scrollbar-width: thin;
                border-bottom: 1px solid #fdf2f8;
            }
            .dm-chat-theme .wa-active-strip::-webkit-scrollbar { height: 4px; }
            .dm-chat-theme .wa-active-strip-item {
                flex: 0 0 auto;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 6px;
                text-decoration: none;
                color: #6b2d52;
                min-width: 64px;
            }
            .dm-chat-theme .wa-active-avatar-wrap {
                position: relative;
                display: inline-block;
            }
            .dm-chat-theme .wa-active-avatar {
                width: 56px;
                height: 56px;
                border-radius: 50%;
                font-size: 18px;
                box-shadow: 0 6px 14px rgba(236, 72, 153, 0.22);
            }
            .dm-chat-theme .wa-online-dot {
                position: absolute;
                top: 2px;
                right: 2px;
                width: 11px;
                height: 11px;
                border-radius: 50%;
                background: #22c55e;
                border: 2px solid #fff;
                box-shadow: 0 1px 3px rgba(0,0,0,.12);
            }
            .dm-chat-theme .wa-active-name {
                font-size: 11px;
                font-weight: 600;
                max-width: 72px;
                text-align: center;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
            .dm-chat-theme .wa-active-strip-item.is-active .wa-active-avatar {
                box-shadow: 0 0 0 3px #f472b6;
            }
            .dm-chat-theme .dm-section-label {
                padding: 10px 18px 4px;
                font-size: 11px;
                font-weight: 800;
                letter-spacing: .12em;
                text-transform: uppercase;
                color: #c084fc;
            }
            .dm-chat-theme .wa-chat-filters {
                border-bottom: none;
                padding-bottom: 4px;
            }
            .dm-chat-theme .wa-user-list {
                background: transparent;
                padding: 4px 10px 12px;
            }
            .dm-chat-theme .wa-user-row > a {
                border-radius: 20px;
                border: 1px solid transparent;
                background: #fff;
                box-shadow: 0 2px 8px rgba(244, 114, 182, 0.06);
            }
            .dm-chat-theme .wa-user-row.active > a {
                background: linear-gradient(90deg, #fff1f7 0%, #fef5ff 100%);
                border-color: #fbcfe8;
            }
            .dm-chat-theme .wa-unread-badge {
                background: linear-gradient(135deg, #ec4899, #db2777);
            }
            .dm-chat-theme .dm-thread-topbar {
                background: rgba(255,255,255,.88);
                backdrop-filter: blur(10px);
                border-bottom: 1px solid #fce7f3;
                padding: 12px 14px;
            }
            .dm-chat-theme .dm-topbar-names {
                flex: 1;
                min-width: 0;
                display: flex;
                flex-direction: column;
                gap: 2px;
            }
            .dm-chat-theme .dm-topbar-status {
                font-size: 12px;
                font-weight: 600;
                color: #64748b;
            }
            .dm-chat-theme .dm-topbar-status.is-online {
                color: #16a34a;
            }
            .dm-chat-theme .dm-topbar-status.is-offline {
                color: #64748b;
            }
            .dm-chat-theme .dm-topbar-online {
                top: 0;
                right: 0;
            }
            .dm-chat-theme .dm-topbar-avatar-wrap {
                border-radius: 50% !important;
            }
            .dm-chat-theme .wa-messages {
                background:
                    url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='140' height='140' viewBox='0 0 140 140'%3E%3Cg fill='none' stroke='%23f6d6e8' stroke-width='1' opacity='0.42'%3E%3Cpath d='M33 28c3-4 9-4 12 0 2 3 2 7-1 10l-8 7-8-7c-3-3-3-7-1-10 3-4 9-4 12 0z'/%3E%3Cpath d='M102 84c3-4 9-4 12 0 2 3 2 7-1 10l-8 7-8-7c-3-3-3-7-1-10 3-4 9-4 12 0z'/%3E%3Cpath d='M68 108c2-3 7-3 9 0 1 2 1 5-1 7l-6 5-6-5c-2-2-2-5-1-7 2-3 7-3 9 0z'/%3E%3C/g%3E%3C/svg%3E") 0 0/140px 140px repeat,
                    linear-gradient(180deg, #fff2fa 0%, #fdeeff 100%);
                padding: 16px 18px 100px;
            }
            .dm-chat-theme .wa-day-sep span {
                background: linear-gradient(135deg, #fce7f3, #fae8ff);
                border: 1px solid #fbcfe8;
                color: #9d174d;
                font-weight: 700;
                border-radius: 999px;
                padding: 6px 16px;
            }
            .dm-chat-theme .wa-bubble { border: none; }
            .dm-chat-theme .wa-bubble::after { display: none !important; content: none !important; }
            .dm-chat-theme .wa-bubble.in {
                background: linear-gradient(135deg, #ffe7f3 0%, #ffd8ec 55%, #ffd0e8 100%);
                color: #5a3047;
                border-radius: 22px 22px 22px 8px;
                box-shadow: 0 8px 18px rgba(190, 24, 93, 0.12);
            }
            .dm-chat-theme .wa-bubble.out {
                background: linear-gradient(135deg, #fb7185 0%, #e879f9 45%, #a855f7 100%);
                color: #fff;
                border-radius: 22px 22px 8px 22px;
                box-shadow: 0 10px 22px rgba(168, 85, 247, 0.28);
            }
            .dm-chat-theme .wa-bubble-wrap.out .wa-bubble-footer {
                color: rgba(255,255,255,0.88);
            }
            .dm-chat-theme .wa-bubble-wrap.out .ticks.sent,
            .dm-chat-theme .wa-bubble-wrap.out .ticks.read {
                color: rgba(255,255,255,0.95);
            }
            .dm-chat-theme .wa-bubble-wrap.in .wa-bubble-footer {
                color: #9a627f;
            }
            .dm-chat-theme .wa-bubble .chat-img {
                border: none;
                box-shadow: 0 8px 18px rgba(15, 23, 42, 0.12);
            }
            .dm-chat-theme .wa-composer {
                left: 18px;
                right: 18px;
                bottom: 16px;
                border-radius: 999px;
                border: 1px solid #f9d0e8;
                background: rgba(255,255,255,.95);
                box-shadow: 0 14px 34px rgba(190, 24, 93, 0.14);
                padding: 6px 8px 6px 10px;
            }
            .dm-chat-theme .wa-composer-line {
                gap: 10px;
            }
            .dm-chat-theme .wa-attach-plus {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                background: linear-gradient(145deg, #f472b6, #ec4899);
                color: transparent;
                font-size: 0;
                box-shadow: 0 8px 18px rgba(236, 72, 153, 0.35);
                transform: none;
                position: relative;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                line-height: 0;
                text-indent: -9999px;
                overflow: hidden;
            }
            .dm-chat-theme .wa-attach-plus::before {
                content: '+';
                position: absolute;
                inset: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-size: 32px;
                font-weight: 400;
                line-height: 1;
                transform: none;
                text-indent: 0;
            }
            .dm-chat-theme .wa-composer-inner {
                border-radius: 999px;
                border: 1px solid #fce7f3;
                background: #fffefb;
                min-height: 48px;
            }
            .dm-chat-theme .wa-send-btn {
                width: 48px;
                height: 48px;
                border-radius: 50%;
                background: linear-gradient(145deg, #ec4899, #db2777);
                font-size: 18px;
                box-shadow: 0 8px 18px rgba(219, 39, 119, 0.35);
            }
            .dm-chat-theme .wa-mic-send-slot .wa-icon-btn {
                border-radius: 50%;
                background: #fdf2f8;
            }

            /* —— Reference UI: soft lavender / blue / pink —— */
            .dm-chat-theme.wa-chat-page {
                background: linear-gradient(160deg, #f5f3ff 0%, #eff6ff 35%, #fdf2f8 100%);
            }
            .dm-chat-theme .wa-inbox,
            .dm-chat-theme .wa-main {
                border: 1px solid rgba(209, 233, 255, 0.85);
                box-shadow: 0 24px 48px rgba(99, 102, 241, 0.08), 0 8px 24px rgba(236, 72, 153, 0.06);
            }
            .dm-chat-theme .wa-inbox {
                display: flex;
                flex-direction: column;
                background: linear-gradient(180deg, #ffffff 0%, #faf5ff 100%);
            }
            .dm-chat-theme .chat-inbox-search-row {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 8px 14px 12px;
                border-bottom: 1px solid rgba(243, 232, 255, 0.9);
            }
            .dm-chat-theme .chat-inbox-search-row .wa-inbox-search {
                flex: 1;
                min-width: 0;
            }
            .dm-chat-theme .chat-inbox-filter-btn {
                width: 44px;
                height: 44px;
                flex-shrink: 0;
                border: none;
                border-radius: 14px;
                background: #fff;
                color: #6366f1;
                cursor: pointer;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                box-shadow: 0 4px 14px rgba(99, 102, 241, 0.12);
                border: 1px solid #e0e7ff;
                transition: transform .15s ease, box-shadow .15s ease;
            }
            .dm-chat-theme .chat-inbox-filter-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 6px 18px rgba(99, 102, 241, 0.18);
            }
            .dm-chat-theme .chat-inbox-tabs {
                display: flex;
                align-items: stretch;
                gap: 0;
                padding: 0 8px;
                background: transparent;
                border-bottom: 1px solid rgba(226, 232, 240, 0.95);
            }
            .dm-chat-theme .chat-inbox-tab {
                flex: 1;
                border: none;
                background: transparent;
                font-family: inherit;
                font-size: 14px;
                font-weight: 600;
                color: #64748b;
                padding: 12px 8px 14px;
                cursor: pointer;
                position: relative;
                border-radius: 0;
                box-shadow: none;
            }
            .dm-chat-theme .chat-inbox-tab:hover {
                color: #4338ca;
            }
            .dm-chat-theme .chat-inbox-tab.is-on {
                color: #2563eb;
                background: transparent;
                box-shadow: none;
            }
            .dm-chat-theme .chat-inbox-tab.is-on::after {
                content: '';
                position: absolute;
                left: 12px;
                right: 12px;
                bottom: 0;
                height: 3px;
                border-radius: 3px 3px 0 0;
                background: linear-gradient(90deg, #3b82f6, #6366f1);
            }
            .dm-chat-theme .chat-inbox-tab .wa-filter-count {
                margin-left: 6px;
                vertical-align: middle;
            }
            .dm-chat-theme .chat-inbox-footer {
                padding: 12px 14px 16px;
                border-top: 1px solid rgba(241, 245, 249, 0.95);
                margin-top: auto;
                flex-shrink: 0;
                background: rgba(255, 255, 255, 0.65);
            }
            .dm-chat-theme .chat-btn-new-match {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
                width: 100%;
                padding: 12px 16px;
                border-radius: 16px;
                font-size: 15px;
                font-weight: 600;
                color: #1e40af;
                text-decoration: none;
                background: linear-gradient(135deg, #dbeafe, #e0e7ff);
                border: 1px solid #bfdbfe;
                box-shadow: 0 6px 16px rgba(59, 130, 246, 0.12);
            }
            .dm-chat-theme .chat-btn-new-match:hover {
                filter: brightness(1.02);
                transform: translateY(-1px);
            }
            .dm-chat-theme .chat-btn-new-match__icon {
                width: 28px;
                height: 28px;
                border-radius: 50%;
                background: #3b82f6;
                color: #fff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 20px;
                font-weight: 400;
                line-height: 1;
            }
            .dm-chat-theme .chat-quick-nav {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
                padding: 20px 10px;
                border-radius: 28px;
                background: linear-gradient(180deg, #fce7f3 0%, #f3e8ff 55%, #e0e7ff 100%);
                border: 1px solid rgba(199, 210, 254, 0.6);
                box-shadow: 0 16px 40px rgba(99, 102, 241, 0.1);
            }
            .dm-chat-theme .chat-quick-nav__item {
                position: relative;
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 6px;
                text-decoration: none;
                color: #475569;
                font-size: 11px;
                font-weight: 600;
                width: 100%;
                max-width: 72px;
                padding: 4px 0;
            }
            .dm-chat-theme .chat-quick-nav__item:hover .chat-quick-nav__circle {
                transform: translateY(-2px);
                box-shadow: 0 8px 18px rgba(99, 102, 241, 0.2);
            }
            .dm-chat-theme .chat-quick-nav__item.is-active .chat-quick-nav__circle {
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.35);
            }
            .dm-chat-theme .chat-quick-nav__circle {
                width: 52px;
                height: 52px;
                border-radius: 50%;
                background: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 22px;
                line-height: 1;
                box-shadow: 0 4px 14px rgba(15, 23, 42, 0.08);
                border: 1px solid #f1f5f9;
                transition: transform .15s ease, box-shadow .15s ease;
            }
            .dm-chat-theme .chat-quick-nav__circle--heart {
                color: #db2777;
            }
            .dm-chat-theme .chat-quick-nav__label {
                text-align: center;
                line-height: 1.15;
            }
            .dm-chat-theme .chat-quick-nav__badge {
                position: absolute;
                top: 2px;
                right: 2px;
                min-width: 18px;
                height: 18px;
                padding: 0 5px;
                border-radius: 999px;
                background: linear-gradient(135deg, #3b82f6, #6366f1);
                color: #fff;
                font-size: 10px;
                font-weight: 700;
                display: flex;
                align-items: center;
                justify-content: center;
                line-height: 1;
            }
            .dm-chat-theme .wa-bubble-wrap.in {
                display: flex;
                flex-direction: row;
                align-items: flex-end;
                gap: 8px;
            }
            .dm-chat-theme .wa-bubble-wrap.out {
                display: flex;
                justify-content: flex-end;
            }
            .dm-chat-theme .wa-bubble-cluster {
                display: flex;
                flex-direction: row;
                align-items: flex-start;
                gap: 8px;
                max-width: calc(100% - 44px);
                min-width: 0;
            }
            .dm-chat-theme .wa-bubble-wrap.out .wa-bubble-cluster {
                flex-direction: row-reverse;
                max-width: 100%;
            }
            .dm-chat-theme .wa-avatar--sm {
                width: 32px;
                height: 32px;
                min-width: 32px;
                font-size: 12px;
                border-radius: 50%;
                text-decoration: none;
                box-shadow: 0 2px 8px rgba(99, 102, 241, 0.15);
            }
            .dm-chat-theme .wa-peer-avatar {
                flex-shrink: 0;
                margin-bottom: 2px;
            }
            .dm-chat-theme .wa-messages {
                background:
                    radial-gradient(circle at 12% 18%, rgba(243, 232, 255, 0.9) 0, transparent 42%),
                    radial-gradient(circle at 88% 12%, rgba(209, 233, 255, 0.75) 0, transparent 38%),
                    linear-gradient(180deg, #faf5ff 0%, #f8fafc 45%, #eff6ff 100%);
            }
            .dm-chat-theme .wa-bubble.in {
                background: linear-gradient(180deg, #f3e8ff 0%, #ede9fe 100%);
                color: #334155;
                border-radius: 20px 20px 20px 8px;
                border: 1px solid rgba(196, 181, 253, 0.45);
                box-shadow: 0 6px 16px rgba(139, 92, 246, 0.1);
            }
            .dm-chat-theme .wa-bubble.out {
                background: linear-gradient(180deg, #dbeafe 0%, #d1e9ff 100%);
                color: #1e293b;
                border-radius: 20px 20px 8px 20px;
                border: 1px solid rgba(147, 197, 253, 0.65);
                box-shadow: 0 6px 16px rgba(59, 130, 246, 0.12);
            }
            .dm-chat-theme .wa-bubble-wrap.out .wa-bubble-footer,
            .dm-chat-theme .wa-bubble-wrap.out .ticks.sent,
            .dm-chat-theme .wa-bubble-wrap.out .ticks.read {
                color: #475569;
            }
            .dm-chat-theme .wa-bubble-wrap.in .wa-bubble-footer {
                color: #64748b;
            }
            .dm-chat-theme .ticks.read {
                color: #2563eb;
            }
            .dm-chat-theme .wa-day-sep span {
                background: rgba(255, 255, 255, 0.92);
                border: 1px solid #e2e8f0;
                color: #475569;
            }
            .dm-chat-theme .wa-topbar .wa-icon-btn {
                width: 42px;
                height: 42px;
                border-radius: 50%;
                background: #fff;
                border: 1px solid #e2e8f0;
                box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
            }
            .dm-chat-theme .chat-composer-plus {
                background: #3b82f6 !important;
                color: #fff !important;
                border: none !important;
                box-shadow: 0 8px 18px rgba(59, 130, 246, 0.35);
            }
            .dm-chat-theme .chat-composer-plus::before {
                color: #fff !important;
            }
            .dm-chat-theme .chat-composer-field {
                border-radius: 22px !important;
                border: 1px solid #e2e8f0 !important;
                background: #fff !important;
                min-height: 50px;
                padding: 4px 8px 4px 6px;
            }
            .dm-chat-theme .chat-composer-iconbtn {
                width: 40px;
                height: 40px;
                border-radius: 12px;
                flex-shrink: 0;
            }
            .dm-chat-theme .chat-composer-send-slot {
                width: auto;
                min-width: 52px;
            }
            .dm-chat-theme .chat-composer-send.is-visible {
                width: 52px;
                height: 52px;
                border-radius: 50%;
                background: linear-gradient(145deg, #3b82f6, #2563eb) !important;
                border: none;
                box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
            }
            .dm-chat-theme .chat-composer-line {
                align-items: center;
            }

            @@media (max-width: 1024px) {
                .dm-chat-theme .chat-quick-nav {
                    display: none;
                }
                .dm-chat-theme .wa-wrap {
                    grid-template-columns: minmax(260px, 340px) minmax(0, 1fr);
                }
            }
            @@media (max-width: 900px) {
                .dm-chat-theme .wa-wrap {
                    grid-template-columns: minmax(280px, 1fr) minmax(0, 1fr);
                }
            }
            @@media (max-width: 768px) {
                .dm-chat-theme.wa-chat-page { padding: 8px 0 0; }
                .dm-chat-theme .wa-wrap { gap: 0; }
                .dm-chat-theme .chat-quick-nav { display: none !important; }
                .dm-chat-theme .wa-chat-stage {
                    flex-direction: column;
                    gap: 0;
                }
            }
        </style>

        @if(session('error'))
            <div class="wa-toast-err">{{ session('error') }}</div>
        @endif

        <div class="wa-wrap @if($activeUser) wa-has-active @endif">
            <aside class="wa-inbox">
                <div class="wa-inbox-top dm-inbox-header chat-inbox-header">
                    <a href="{{ route('user.discover') }}" class="dm-head-circle-btn" title="Back" aria-label="Back">‹</a>
                    <h1 class="wa-inbox-title dm-inbox-title-center">Messages</h1>
                    <div class="wa-inbox-top-actions">
                        <a href="{{ route('user.discover') }}" class="dm-head-circle-btn dm-head-plus" title="Discover" aria-label="Discover">+</a>
                        <div class="wa-menu-wrap" id="waInboxMenuWrap">
                            <button type="button" class="dm-head-circle-btn" id="waInboxMenuBtn" aria-expanded="false" aria-haspopup="true" aria-label="More options">⋮</button>
                            <div class="wa-dropdown wa-inbox-dd" id="waInboxMenu" role="menu">
                                <a class="wa-menu-item" role="menuitem" href="{{ route('user.settings.profile') }}">Settings</a>
                                <a class="wa-menu-item" role="menuitem" href="{{ route('user.help') }}">Help</a>
                                <a class="wa-menu-item" role="menuitem" href="{{ route('user.blocked.index') }}">Blocked</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="wa-inbox-search-wrap chat-inbox-search-row">
                    <input type="search" class="wa-inbox-search dm-search-pill" id="waChatSearch" placeholder="Search conversations…" autocomplete="off" aria-label="Search chats">
                    <button type="button" class="chat-inbox-filter-btn" id="waInboxSearchFilterBtn" title="More" aria-label="Open menu">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="5" r="1.5" fill="currentColor"/><circle cx="12" cy="12" r="1.5" fill="currentColor"/><circle cx="12" cy="19" r="1.5" fill="currentColor"/></svg>
                    </button>
                </div>
                @if(($chatSidebar ?? collect())->isNotEmpty())
                <div class="wa-active-strip" aria-label="Recent chats">
                    @foreach(($chatSidebar ?? collect())->take(14) as $stripRow)
                        @php
                            $su = $stripRow['user'];
                            $suLastSeen = $su->last_seen_at ? \Carbon\Carbon::parse($su->last_seen_at) : null;
                            $suIsOnline = $suLastSeen && $suLastSeen->gt(now()->subMinutes(5));
                        @endphp
                        <a href="{{ route('user.chat.with', $su->id) }}" class="wa-active-strip-item @if($activeUser && $activeUser->id === $su->id) is-active @endif">
                            <span class="wa-active-avatar-wrap">
                                <span class="wa-avatar wa-active-avatar">{{ strtoupper(\Illuminate\Support\Str::substr($su->name ?? 'U', 0, 1)) }}</span>
                                @if($suIsOnline)
                                    <span class="wa-online-dot" aria-hidden="true"></span>
                                @endif
                            </span>
                            <span class="wa-active-name">{{ \Illuminate\Support\Str::limit($su->name ?? 'User', 8) }}</span>
                        </a>
                    @endforeach
                </div>
                @endif
                <div class="wa-chat-filters chat-inbox-tabs" id="waChatFilters" role="tablist">
                    <button type="button" class="chat-inbox-tab is-on" data-filter="all" role="tab" aria-selected="true">All</button>
                    <button type="button" class="chat-inbox-tab" data-filter="unread" role="tab" aria-selected="false">Unread
                        @if(($totalChatUnread ?? 0) > 0)
                            <span class="wa-filter-count">{{ ($totalChatUnread ?? 0) > 99 ? '99+' : $totalChatUnread }}</span>
                        @endif
                    </button>
                    <button type="button" class="chat-inbox-tab" data-filter="favourites" role="tab" aria-selected="false">Favourites</button>
                </div>
                <div class="wa-user-list" id="waUserList">
                    @forelse(($chatSidebar ?? collect()) as $row)
                        @php $u = $row['user']; @endphp
                        <div class="wa-user-row @if($activeUser && $activeUser->id === $u->id) active @endif"
                            data-chat-name="{{ e($u->name ?? '') }}"
                            data-user-id="{{ $u->id }}"
                            data-unread="{{ (int) ($row['unread'] ?? 0) }}">
                            <a href="{{ route('user.chat.with', $u->id) }}">
                                <div class="wa-avatar">{{ strtoupper(\Illuminate\Support\Str::substr($u->name ?? 'U', 0, 1)) }}</div>
                                <div class="wa-user-row-meta">
                                    <div class="wa-user-row-top">
                                        <span class="wa-user-row-name text-truncate">{{ $u->name }}</span>
                                        @if(!empty($row['time_label']))
                                            <span class="wa-user-row-time">{{ $row['time_label'] }}</span>
                                        @endif
                                    </div>
                                    <div style="display:flex;align-items:center;gap:8px;min-width:0;">
                                        <span class="wa-user-row-preview">{{ $row['preview'] ?? '' }}</span>
                                        @if(($row['unread'] ?? 0) > 0)
                                            <span class="wa-unread-badge" aria-label="Unread messages">{{ ($row['unread'] > 99) ? '99+' : $row['unread'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                            <button type="button" class="wa-fav-btn" data-fav-user="{{ $u->id }}" title="Favourite chat" aria-label="Favourite">☆</button>
                        </div>
                    @empty
                        <div style="padding:24px;color:var(--wa-muted);text-align:center;">No conversations yet.<br><small>Accept a proposal to chat.</small></div>
                    @endforelse
                </div>
                <div class="chat-inbox-footer">
                    <a href="{{ route('user.discover') }}" class="chat-btn-new-match">
                        <span class="chat-btn-new-match__icon" aria-hidden="true">+</span>
                        New match
                    </a>
                </div>
            </aside>

            <div class="wa-chat-stage">
            <main class="wa-main">
                @if($activeUser)
                    @php
                        $activeLastSeen = $activeUser->last_seen_at ? \Carbon\Carbon::parse($activeUser->last_seen_at) : null;
                        $activeIsOnline = $activeLastSeen && $activeLastSeen->gt(now()->subMinutes(5));
                        $activeStatus = $activeIsOnline
                            ? 'Online'
                            : ($activeLastSeen
                                ? 'Last online: ' . ($activeLastSeen->isToday()
                                    ? $activeLastSeen->format('h:i A')
                                    : $activeLastSeen->format('d M, h:i A'))
                                : 'Last online: --');
                    @endphp
                    <div class="wa-topbar dm-thread-topbar">
                        <a href="{{ route('user.chat.index') }}" class="wa-back-mob dm-head-circle-btn" title="Back to chats" aria-label="Back to chats">‹</a>
                        <a href="{{ route('user.profile.view', $activeUser) }}" class="wa-avatar dm-topbar-avatar-wrap" style="width:44px;height:44px;font-size:15px;text-decoration:none;color:#fff;position:relative;">
                            {{ strtoupper(\Illuminate\Support\Str::substr($activeUser->name ?? 'U', 0, 1)) }}
                            @if($activeIsOnline)
                                <span class="wa-online-dot dm-topbar-online" aria-hidden="true"></span>
                            @endif
                        </a>
                        <div class="dm-topbar-names">
                            <a href="{{ route('user.profile.view', $activeUser) }}" class="wa-topbar-name text-truncate" style="text-decoration:none;color:inherit;">{{ $activeUser->name }}</a>
                            <span class="dm-topbar-status {{ $activeIsOnline ? 'is-online' : 'is-offline' }}">{{ $activeStatus }}</span>
                        </div>
                        <div class="wa-topbar-actions">
                            <button type="button" class="wa-icon-btn" title="Search in chat" id="waBtnSearchMsgs" aria-expanded="false">🔎</button>
                            <a class="wa-icon-btn" title="Voice call" href="{{ route('user.chat.call', [$activeUser, 'type' => 'audio', 'role' => 'caller']) }}">📞</a>
                            <a class="wa-icon-btn" title="Video call" href="{{ route('user.chat.call', [$activeUser, 'type' => 'video', 'role' => 'caller']) }}">📹</a>
                            <div class="wa-menu-wrap" id="waMenuWrap">
                                <button type="button" class="wa-icon-btn" id="waBtnMore" aria-expanded="false" aria-haspopup="true" aria-label="More options">⋮</button>
                                <div class="wa-dropdown" id="waChatMoreMenu" role="menu">
                                    <button type="button" class="wa-menu-item" data-menu="profile" role="menuitem"><span class="mi">👤</span>Contact info</button>
                                    <button type="button" class="wa-menu-item" data-menu="search" role="menuitem"><span class="mi">🔎</span>Search</button>
                                    <button type="button" class="wa-menu-item" data-menu="select" role="menuitem"><span class="mi">☑</span>Select messages</button>
                                    <button type="button" class="wa-menu-item" data-menu="starred" role="menuitem"><span class="mi">★</span>Starred messages</button>
                                    <button type="button" class="wa-menu-item" data-menu="read-all" role="menuitem"><span class="mi">✓</span>Mark all as read</button>
                                    <div class="wa-menu-sep"></div>
                                    <button type="button" class="wa-menu-item" data-menu="settings" role="menuitem"><span class="mi">⚙</span>Chat settings</button>
                                    <button type="button" class="wa-menu-item danger" data-menu="block" role="menuitem"><span class="mi">🚫</span>Block</button>
                                    <button type="button" class="wa-menu-item danger" data-menu="delete" role="menuitem"><span class="mi">🗑</span>Delete messages</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wa-msg-search-row" id="waMsgSearchRow">
                        <input type="search" id="waMsgSearchInput" placeholder="Search in conversation…" autocomplete="off" aria-label="Search in conversation">
                    </div>

                    <div class="wa-select-bar" id="waSelectBar">
                        <strong id="waSelectCount">0 selected</strong>
                        <button type="button" class="wa-btn-chat" id="waSelectAll">Select all</button>
                        <button type="button" class="wa-btn-chat" id="waSelectNone">Clear</button>
                        <span style="flex:1"></span>
                        <button type="button" class="wa-btn-chat" id="waBtnCopy" disabled>Copy text</button>
                        <button type="button" class="wa-btn-chat" id="waBtnStar" disabled>Star</button>
                        <button type="button" class="wa-btn-chat" id="waBtnUnstar" disabled>Unstar</button>
                        <button type="button" class="wa-btn-chat" id="waBtnDeleteMe">Delete for me</button>
                        <button type="button" class="wa-btn-chat primary" id="waBtnDeleteEveryone" disabled>Unsend for everyone</button>
                        <button type="button" class="wa-btn-chat" id="waBtnCancelSelect">Done</button>
                    </div>

                    <div class="wa-messages" id="waChatScroll">
                        @foreach ($messages as $msg)
                            @if($loop->first || $messages[$loop->index - 1]->created_at->toDateString() !== $msg->created_at->toDateString())
                                @php
                                    $d = $msg->created_at;
                                    $dayLabel = $d->isToday() ? 'Today' : ($d->isYesterday() ? 'Yesterday' : $d->format('M j, Y'));
                                @endphp
                                <div class="wa-day-sep"><span>{{ $dayLabel }}</span></div>
                            @endif
                            @php
                                $isOut = $msg->sender_id == auth()->id();
                                $copyPlain = $msg->previewPlain();
                                $starredMe = $msg->isStarredBy(auth()->id());
                                $searchBlob = '';
                                if (! $msg->isRevoked()) {
                                    $searchBlob = \Illuminate\Support\Str::lower(trim($copyPlain.' '.strip_tags((string) $msg->message)));
                                }
                            @endphp
                            <div class="wa-bubble-wrap {{ $isOut ? 'out' : 'in' }}" data-msg-id="{{ $msg->id }}" data-sender-id="{{ $msg->sender_id }}" data-created="{{ $msg->created_at->toIso8601String() }}" data-day-key="{{ $msg->created_at->toDateString() }}" data-revoked="{{ $msg->isRevoked() ? '1' : '0' }}" data-starred="{{ $starredMe ? '1' : '0' }}" data-copy-text="{{ e($copyPlain) }}" data-search-text="{{ e($searchBlob) }}">
                                @if(!$isOut)
                                    <a href="{{ route('user.profile.view', $activeUser) }}" class="wa-peer-avatar wa-avatar wa-avatar--sm" title="{{ $activeUser->name }}">{{ strtoupper(\Illuminate\Support\Str::substr($activeUser->name ?? 'U', 0, 1)) }}</a>
                                @endif
                                <div class="wa-bubble-cluster">
                                <label class="wa-msg-check"><input type="checkbox" class="wa-msg-cb" value="{{ $msg->id }}"></label>
                                <div class="wa-bubble {{ $isOut ? 'out' : 'in' }}">
                                    @if($msg->isRevoked())
                                        <div class="wa-revoked">{{ $isOut ? 'You deleted this message' : 'This message was deleted' }}</div>
                                    @else
                                        @php
                                            $mediaUrl = $msg->media_path ? asset('storage/'.$msg->media_path) : null;
                                            $extLower = $msg->media_path ? strtolower(pathinfo($msg->media_path, PATHINFO_EXTENSION)) : '';
                                            $isImgMedia = $msg->isImage() || ($mediaUrl && in_array($extLower, ['jpg','jpeg','png','gif','webp'], true));
                                            $isAudMedia = $msg->isAudio() || ($mediaUrl && in_array($extLower, ['webm','ogg','opus','mp3','wav','m4a','mp4','mpeg','oga'], true));
                                        @endphp
                                        @if($mediaUrl && $isImgMedia)
                                            <img src="{{ $mediaUrl }}" alt="" class="chat-img">
                                        @elseif($mediaUrl && $isAudMedia)
                                            <audio controls preload="metadata" src="{{ $mediaUrl }}"></audio>
                                        @else
                                            {{ $msg->message }}
                                        @endif
                                    @endif
                                    <div class="wa-bubble-footer">
                                        @if($starredMe)<span class="wa-star-icon" title="Starred">★</span>@endif
                                        <span>{{ $msg->created_at->format('H:i') }}</span>
                                        @if($isOut && !$msg->isRevoked())
                                            @if($msg->read_at)
                                                <span class="ticks read" title="Read">
                                                    <svg width="20" height="12" viewBox="0 0 20 12" aria-hidden="true"><path d="M2.5 6.2L5.8 9.5L9.5 3" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.5 6.2L10.8 9.5L17.5 2" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                </span>
                                            @else
                                                <span class="ticks sent" title="Delivered">
                                                    <svg width="14" height="12" viewBox="0 0 14 12" aria-hidden="true"><path d="M2.5 6.2L5.8 9.5L11.5 2.5" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <form method="POST" action="{{ route('user.chat.send', $activeUser->id) }}" class="wa-composer chat-composer" id="waTextForm">
                        @csrf
                        <div class="wa-composer-line chat-composer-line">
                            <label class="wa-attach-plus chat-composer-plus" title="Attach photo" for="waPickImage" aria-label="Attach photo">+</label>
                            <input type="file" name="file" accept="image/*" class="d-none" id="waPickImage">
                            <div class="wa-composer-inner-wrap" style="position:relative;flex:1;min-width:0;">
                                <div class="wa-composer-inner chat-composer-field">
                                    <div class="wa-composer-emoji-wrap">
                                        <button type="button" class="wa-icon-btn chat-composer-iconbtn" id="waEmojiBtn" title="Emoji" aria-label="Emoji">😊</button>
                                        <div class="wa-emoji-pop" id="waEmojiPop" aria-hidden="true"></div>
                                    </div>
                                    <input type="text" name="message" id="waTextInput" placeholder="Type a message…" autocomplete="off">
                                    <button type="button" class="wa-icon-btn chat-composer-iconbtn" id="waGalleryBtn" title="Photo" aria-label="Attach image">🖼</button>
                                    <button type="button" class="wa-icon-btn chat-composer-iconbtn" id="waMicBtn" title="Voice message" aria-label="Voice message">🎤</button>
                                </div>
                            </div>
                            <div class="wa-mic-send-slot chat-composer-send-slot">
                                <button type="submit" class="wa-send-btn chat-composer-send" id="waSendBtn" title="Send" aria-label="Send">➤</button>
                            </div>
                        </div>
                        <div class="wa-rec-hint" id="waRecHint"></div>
                    </form>

                    <div class="wa-modal" id="waModalStarred" aria-hidden="true">
                        <div class="wa-modal-backdrop" data-close-modal></div>
                        <div class="wa-modal-box">
                            <div class="wa-modal-head">
                                Starred messages
                                <button type="button" class="wa-modal-close" data-close-modal title="Close">&times;</button>
                            </div>
                            <div class="wa-modal-body" id="waStarredBody">
                                <p style="color:var(--wa-muted);font-size:14px;">Loading…</p>
                            </div>
                        </div>
                    </div>

                    <div class="wa-modal" id="waModalSettings" aria-hidden="true">
                        <div class="wa-modal-backdrop" data-close-modal></div>
                        <div class="wa-modal-box">
                            <div class="wa-modal-head">
                                Chat settings
                                <button type="button" class="wa-modal-close" data-close-modal title="Close">&times;</button>
                            </div>
                            <div class="wa-modal-body">
                                <a class="wa-sett-link" href="{{ route('user.profile.view', $activeUser) }}">View {{ $activeUser->name }}’s profile</a>
                                <a class="wa-sett-link" href="{{ route('user.settings.privacy') }}">Privacy &amp; safety</a>
                                <a class="wa-sett-link" href="{{ route('user.settings.notifications') }}">Notifications</a>
                                <a class="wa-sett-link" href="{{ route('user.blocked.index') }}">Blocked users</a>
                                <a class="wa-sett-link" href="{{ route('user.help') }}">Help</a>
                                <a class="wa-sett-link" href="{{ route('user.chat.call', [$activeUser, 'type' => 'audio', 'role' => 'answer']) }}">Join call as receiver (audio)</a>
                            </div>
                        </div>
                    </div>

                    <div class="wa-modal" id="waModalDeleteChoices" aria-hidden="true">
                        <div class="wa-modal-backdrop" data-close-modal></div>
                        <div class="wa-modal-box" style="max-width:360px;">
                            <div class="wa-modal-head">
                                Delete messages
                                <button type="button" class="wa-modal-close" data-close-modal title="Close">&times;</button>
                            </div>
                            <div class="wa-modal-body">
                                <p style="font-size:14px;color:var(--wa-muted);margin-bottom:12px;">Choose how to delete selected messages.</p>
                                <div style="display:flex;gap:10px;justify-content:flex-end;flex-wrap:wrap;">
                                    <button type="button" class="wa-btn-chat" id="waModalDeleteMe">Delete for me</button>
                                    <button type="button" class="wa-btn-chat primary" id="waModalDeleteEveryone">Delete for everyone</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wa-toast-mini" id="waToast" role="status"></div>
                    <div class="wa-msg-menu" id="waMsgMenu" aria-hidden="true">
                        <div class="wa-msg-reacts" id="waMsgReactRow">
                            <button type="button" data-react="👍">👍</button>
                            <button type="button" data-react="❤️">❤️</button>
                            <button type="button" data-react="😂">😂</button>
                            <button type="button" data-react="😮">😮</button>
                            <button type="button" data-react="🙏">🙏</button>
                            <button type="button" data-react="+">+</button>
                        </div>
                        <button type="button" class="wa-msg-act" data-act="reply"><span class="mi">↩</span>Reply</button>
                        <button type="button" class="wa-msg-act" data-act="copy"><span class="mi">📄</span>Copy</button>
                        <button type="button" class="wa-msg-act" data-act="react"><span class="mi">😊</span>React</button>
                        <button type="button" class="wa-msg-act" data-act="forward"><span class="mi">↪</span>Forward</button>
                        <button type="button" class="wa-msg-act" data-act="pin"><span class="mi">📌</span>Pin</button>
                        <button type="button" class="wa-msg-act" data-act="star"><span class="mi">★</span>Star</button>
                        <button type="button" class="wa-msg-act" data-act="delete"><span class="mi">🗑</span>Delete</button>
                    </div>
                @else
                    <div class="wa-intro" role="region" aria-label="Welcome">
                        <div class="wa-intro-visual" aria-hidden="true"></div>
                        <h2>Messaging</h2>
                        <p>Send and receive messages with your matches. Select a chat from the list or accept a proposal to unlock a conversation.</p>
                    </div>
                @endif
            </main>

            <nav class="chat-quick-nav" aria-label="Quick navigation">
                <a href="{{ route('user.likes.index') }}" class="chat-quick-nav__item" title="Likes">
                    <span class="chat-quick-nav__circle chat-quick-nav__circle--heart" aria-hidden="true">❤</span>
                    <span class="chat-quick-nav__label">Matches</span>
                </a>
                <a href="{{ route('user.chat.index') }}" class="chat-quick-nav__item is-active" id="chatQuickNavRecent" title="Messages">
                    <span class="chat-quick-nav__circle" aria-hidden="true">🕐</span>
                    <span class="chat-quick-nav__label">Recent</span>
                    @if(($totalChatUnread ?? 0) > 0)
                        <span class="chat-quick-nav__badge">{{ ($totalChatUnread ?? 0) > 99 ? '99+' : $totalChatUnread }}</span>
                    @endif
                </a>
                <a href="{{ route('user.requests.index') }}" class="chat-quick-nav__item" title="Requests">
                    <span class="chat-quick-nav__circle" aria-hidden="true">👥</span>
                    <span class="chat-quick-nav__label">Requests</span>
                </a>
                <a href="{{ route('user.settings.profile') }}" class="chat-quick-nav__item" title="Settings">
                    <span class="chat-quick-nav__circle" aria-hidden="true">⚙</span>
                    <span class="chat-quick-nav__label">Settings</span>
                </a>
            </nav>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    var FAV_KEY = 'dating_chat_favourites_v1';
    var sidebarPollUrl = @json(route('user.chat.sidebar.poll'));
    var activeUserId = @json($activeUser ? (int) $activeUser->id : 0);
    var sidebarPollTimer = null;
    var sidebarPollBusy = false;
    function getFavs() {
        try {
            var a = JSON.parse(localStorage.getItem(FAV_KEY) || '[]');
            return a.map(Number).filter(function (n) { return n > 0; });
        } catch (e) { return []; }
    }
    function setFavs(ids) {
        localStorage.setItem(FAV_KEY, JSON.stringify(ids));
    }
    function rowMatchesSearch(row, q) {
        if (!q) return true;
        var n = (row.getAttribute('data-chat-name') || '').toLowerCase();
        return n.indexOf(q) !== -1;
    }
    function rowMatchesFilter(row, filter) {
        if (filter === 'all') return true;
        if (filter === 'unread') return parseInt(row.getAttribute('data-unread') || '0', 10) > 0;
        if (filter === 'favourites') {
            var id = parseInt(row.getAttribute('data-user-id') || '0', 10);
            return getFavs().indexOf(id) !== -1;
        }
        return true;
    }
    function applyListFilters() {
        var s = document.getElementById('waChatSearch');
        var q = (s && s.value) ? s.value.toLowerCase().trim() : '';
        var filter = 'all';
        var pills = document.querySelectorAll('#waChatFilters [data-filter].is-on');
        if (pills.length) filter = pills[0].getAttribute('data-filter') || 'all';
        document.querySelectorAll('.wa-user-row[data-chat-name]').forEach(function (row) {
            var ok = rowMatchesSearch(row, q) && rowMatchesFilter(row, filter);
            row.style.display = ok ? '' : 'none';
        });
    }
    function setRowUnread(userId, unread) {
        const row = document.querySelector('.wa-user-row[data-user-id="' + String(userId) + '"]');
        if (!row) return;
        const n = Math.max(0, parseInt(String(unread), 10) || 0);
        row.setAttribute('data-unread', String(n));
        const slot = row.querySelector('.wa-user-row-preview')?.parentElement;
        if (!slot) return;
        const oldBadge = row.querySelector('.wa-unread-badge');
        if (oldBadge) oldBadge.remove();
        if (n > 0) {
            const b = document.createElement('span');
            b.className = 'wa-unread-badge';
            b.setAttribute('aria-label', 'Unread messages');
            b.textContent = n > 99 ? '99+' : String(n);
            slot.appendChild(b);
        }
    }
    function syncGlobalUnreadUi() {
        const total = Array.from(document.querySelectorAll('.wa-user-row[data-user-id]'))
            .map(r => parseInt(r.getAttribute('data-unread') || '0', 10) || 0)
            .reduce((a, b) => a + b, 0);

        const recentNav = document.getElementById('chatQuickNavRecent');
        if (recentNav) {
            let navBadge = recentNav.querySelector('.chat-quick-nav__badge');
            if (total > 0) {
                if (!navBadge) {
                    navBadge = document.createElement('span');
                    navBadge.className = 'chat-quick-nav__badge';
                    recentNav.appendChild(navBadge);
                }
                navBadge.textContent = total > 99 ? '99+' : String(total);
                navBadge.style.display = '';
            } else if (navBadge) {
                navBadge.remove();
            }
        }

        const unreadBtn = document.querySelector('#waChatFilters [data-filter="unread"]');
        if (unreadBtn) {
            let cnt = unreadBtn.querySelector('.wa-filter-count');
            if (total > 0) {
                if (!cnt) {
                    cnt = document.createElement('span');
                    cnt.className = 'wa-filter-count';
                    unreadBtn.appendChild(cnt);
                }
                cnt.textContent = total > 99 ? '99+' : String(total);
            } else if (cnt) {
                cnt.remove();
            }
        }
    }
    function updateSidebarRowFromServer(item) {
        if (!item || !item.user_id) return;
        var row = document.querySelector('.wa-user-row[data-user-id="' + String(item.user_id) + '"]');
        if (!row) return;

        if (item.name) {
            row.setAttribute('data-chat-name', String(item.name));
            var nameEl = row.querySelector('.wa-user-row-name');
            if (nameEl) nameEl.textContent = String(item.name);
        }
        var previewEl = row.querySelector('.wa-user-row-preview');
        if (previewEl) previewEl.textContent = String(item.preview || '');

        var top = row.querySelector('.wa-user-row-top');
        if (top) {
            var timeEl = row.querySelector('.wa-user-row-time');
            var t = String(item.time_label || '').trim();
            if (!timeEl && t) {
                timeEl = document.createElement('span');
                timeEl.className = 'wa-user-row-time';
                top.appendChild(timeEl);
            }
            if (timeEl) {
                timeEl.textContent = t;
                timeEl.style.display = t ? '' : 'none';
            }
        }

        var unread = parseInt(item.unread || 0, 10) || 0;
        if (activeUserId > 0 && Number(item.user_id) === Number(activeUserId)) {
            unread = 0;
        }
        setRowUnread(item.user_id, unread);
    }
    function reorderSidebarRowsByServer(rows) {
        var list = document.getElementById('waUserList');
        if (!list || !Array.isArray(rows) || !rows.length) return;
        for (var i = rows.length - 1; i >= 0; i--) {
            var item = rows[i];
            if (!item || !item.user_id) continue;
            var row = list.querySelector('.wa-user-row[data-user-id="' + String(item.user_id) + '"]');
            if (row) list.prepend(row);
        }
    }
    async function pollSidebarLive() {
        if (sidebarPollBusy || document.hidden) return;
        sidebarPollBusy = true;
        try {
            var url = sidebarPollUrl + '?active_user_id=' + encodeURIComponent(String(activeUserId || 0));
            var r = await fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            var data = await r.json().catch(function () { return {}; });
            if (!r.ok || !data.success || !Array.isArray(data.rows)) return;
            data.rows.forEach(updateSidebarRowFromServer);
            reorderSidebarRowsByServer(data.rows);
            syncGlobalUnreadUi();
            applyListFilters();
        } catch (e) {
            // noop
        } finally {
            sidebarPollBusy = false;
        }
    }
    var s = document.getElementById('waChatSearch');
    if (s) s.addEventListener('input', applyListFilters);

    document.querySelectorAll('#waChatFilters [data-filter]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('#waChatFilters [data-filter]').forEach(function (b) {
                b.classList.remove('is-on');
                b.setAttribute('aria-selected', 'false');
            });
            btn.classList.add('is-on');
            btn.setAttribute('aria-selected', 'true');
            applyListFilters();
        });
    });

    document.querySelectorAll('.wa-fav-btn').forEach(function (btn) {
        var id = parseInt(btn.getAttribute('data-fav-user') || '0', 10);
        if (getFavs().indexOf(id) !== -1) {
            btn.classList.add('is-on');
            btn.textContent = '★';
        }
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var uid = parseInt(btn.getAttribute('data-fav-user') || '0', 10);
            if (!uid) return;
            var favs = getFavs();
            var i = favs.indexOf(uid);
            if (i === -1) { favs.push(uid); btn.classList.add('is-on'); btn.textContent = '★'; }
            else { favs.splice(i, 1); btn.classList.remove('is-on'); btn.textContent = '☆'; }
            setFavs(favs);
            applyListFilters();
        });
    });

    var inboxWrap = document.getElementById('waInboxMenuWrap');
    var inboxBtn = document.getElementById('waInboxMenuBtn');
    function closeInboxMenu() {
        if (inboxWrap) inboxWrap.classList.remove('is-open');
        if (inboxBtn) inboxBtn.setAttribute('aria-expanded', 'false');
    }
    inboxBtn && inboxBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        var open = !inboxWrap.classList.contains('is-open');
        inboxWrap.classList.toggle('is-open', open);
        inboxBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    document.getElementById('waInboxSearchFilterBtn')?.addEventListener('click', function (e) {
        e.stopPropagation();
        if (inboxBtn) inboxBtn.click();
    });
    inboxWrap && inboxWrap.addEventListener('click', function (e) { e.stopPropagation(); });
    document.addEventListener('click', function () { closeInboxMenu(); });
    @if($activeUser)
    setRowUnread({{ (int) $activeUser->id }}, 0);
    syncGlobalUnreadUi();
    @endif
    applyListFilters();
    sidebarPollTimer = setInterval(pollSidebarLive, 1000);
    pollSidebarLive();
})();
</script>

@if($activeUser)
<script>
(function () {
    const scrollEl = document.getElementById('waChatScroll');
    if (scrollEl) scrollEl.scrollTop = scrollEl.scrollHeight;

    function syncComposer() {
        const inp = document.getElementById('waTextInput');
        const send = document.getElementById('waSendBtn');
        const mic = document.getElementById('waMicBtn');
        if (!inp || !send || !mic) return;
        const has = inp.value.trim().length > 0;
        send.classList.toggle('is-visible', has);
        mic.style.display = has ? 'none' : '';
    }
    document.getElementById('waTextInput')?.addEventListener('input', syncComposer);
    syncComposer();

    const emojis = ['😀', '😃', '😂', '🥰', '😍', '😊', '👍', '👎', '🙏', '💕', '❤️', '🔥', '✨', '🎉', '😢', '😮', '💬', '👋', '🤝', '💐', '🌹', '🙌', '💯', '⭐', '🤔', '😴'];
    const waEmojiPop = document.getElementById('waEmojiPop');
    if (waEmojiPop) {
        waEmojiPop.innerHTML = emojis.map(function (ch) {
            return '<button type="button" class="wa-emoji-cell" data-ch="' + ch + '">' + ch + '</button>';
        }).join('');
        waEmojiPop.addEventListener('click', function (e) {
            const b = e.target.closest('[data-ch]');
            if (!b) return;
            const inp = document.getElementById('waTextInput');
            if (inp) {
                inp.value += b.getAttribute('data-ch');
                syncComposer();
                inp.focus();
            }
            waEmojiPop.classList.remove('is-on');
            waEmojiPop.setAttribute('aria-hidden', 'true');
        });
    }
    document.getElementById('waEmojiBtn')?.addEventListener('click', function (e) {
        e.stopPropagation();
        const on = !waEmojiPop?.classList.contains('is-on');
        waEmojiPop?.classList.toggle('is-on', on);
        if (waEmojiPop) waEmojiPop.setAttribute('aria-hidden', on ? 'false' : 'true');
    });
    document.addEventListener('click', function (e) {
        if (!waEmojiPop?.classList.contains('is-on')) return;
        if (e.target.closest('.wa-composer-emoji-wrap')) return;
        waEmojiPop.classList.remove('is-on');
        waEmojiPop.setAttribute('aria-hidden', 'true');
    });

    const waBtnSearchMsgs = document.getElementById('waBtnSearchMsgs');
    const waMsgSearchRow = document.getElementById('waMsgSearchRow');
    const waMsgSearchInput = document.getElementById('waMsgSearchInput');
    function filterMsgsInChat() {
        const q = (waMsgSearchInput?.value || '').toLowerCase().trim();
        document.querySelectorAll('#waChatScroll .wa-bubble-wrap').forEach(function (el) {
            const t = (el.getAttribute('data-search-text') || '').toLowerCase();
            el.style.display = (!q || t.indexOf(q) !== -1) ? '' : 'none';
        });
    }
    waBtnSearchMsgs?.addEventListener('click', function (e) {
        e.stopPropagation();
        const on = !waMsgSearchRow?.classList.contains('is-on');
        waMsgSearchRow?.classList.toggle('is-on', on);
        waBtnSearchMsgs.setAttribute('aria-expanded', on ? 'true' : 'false');
        if (on) {
            waMsgSearchInput?.focus();
        } else if (waMsgSearchInput) {
            waMsgSearchInput.value = '';
            filterMsgsInChat();
        }
    });
    waMsgSearchInput?.addEventListener('input', filterMsgsInChat);

    const csrf = '{{ csrf_token() }}';
    const mediaUrl = @json(route('user.chat.media', $activeUser->id));
    const pick = document.getElementById('waPickImage');
    if (pick) {
        pick.addEventListener('change', async function () {
            if (!pick.files || !pick.files[0]) return;
            const fd = new FormData();
            fd.append('file', pick.files[0]);
            fd.append('_token', csrf);
            pick.value = '';
            try {
                const r = await fetch(mediaUrl, {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    credentials: 'same-origin'
                });
                const data = await r.json().catch(() => ({}));
                if (r.ok && data.success) {
                    window.location.href = data.redirect || window.location.href;
                } else {
                    alert(data.message || 'Could not send photo. Check file type (JPG, PNG, GIF, WebP).');
                }
            } catch (e) {
                alert('Could not send photo.');
            }
        });
    }

    let rec = null;
    let chunks = [];
    const micBtn = document.getElementById('waMicBtn');
    const hint = document.getElementById('waRecHint');

    if (micBtn && navigator.mediaDevices?.getUserMedia) {
        let recording = false;
        micBtn.addEventListener('click', async function () {
            if (!recording) {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    chunks = [];
                    rec = new MediaRecorder(stream);
                    rec.ondataavailable = e => { if (e.data.size) chunks.push(e.data); };
                    rec.onstop = async () => {
                        stream.getTracks().forEach(t => t.stop());
                        recording = false;
                        micBtn.textContent = '🎤';
                        hint.textContent = '';
                        if (!chunks.length) return;
                        const blob = new Blob(chunks, { type: rec.mimeType || 'audio/webm' });
                        const ext = blob.type.includes('webm') ? 'webm' : 'ogg';
                        const fd = new FormData();
                        fd.append('file', blob, 'voice.' + ext);
                        fd.append('_token', csrf);
                        hint.textContent = 'Sending voice…';
                        try {
                            const r = await fetch(mediaUrl, {
                                method: 'POST',
                                body: fd,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrf
                                },
                                credentials: 'same-origin'
                            });
                            const data = await r.json().catch(() => ({}));
                            if (r.ok && data.success) {
                                window.location.href = data.redirect || window.location.href;
                            } else {
                                hint.textContent = '';
                                alert(data.message || 'Voice send failed.');
                            }
                        } catch (e) {
                            hint.textContent = '';
                            alert('Voice send failed.');
                        }
                    };
                    rec.start();
                    recording = true;
                    micBtn.textContent = '⏹';
                    hint.textContent = 'Recording… tap mic again to stop & send';
                } catch (e) {
                    alert('Microphone permission denied or not available.');
                }
            } else if (rec && rec.state === 'recording') {
                rec.stop();
            }
        });
    } else if (micBtn) {
        micBtn.style.opacity = '.4';
        micBtn.title = 'Voice not supported in this browser';
    }

    document.getElementById('waGalleryBtn')?.addEventListener('click', function () {
        document.getElementById('waPickImage')?.click();
    });

    const waMain = document.querySelector('.wa-main');
    const waBtnCancelSelect = document.getElementById('waBtnCancelSelect');
    const waSelectBar = document.getElementById('waSelectBar');
    const waSelectCount = document.getElementById('waSelectCount');
    const waSelectAll = document.getElementById('waSelectAll');
    const waSelectNone = document.getElementById('waSelectNone');
    const waBtnCopy = document.getElementById('waBtnCopy');
    const waBtnStar = document.getElementById('waBtnStar');
    const waBtnUnstar = document.getElementById('waBtnUnstar');
    const waBtnDeleteMe = document.getElementById('waBtnDeleteMe');
    const waBtnDeleteEveryone = document.getElementById('waBtnDeleteEveryone');
    const deleteMessagesUrl = @json(route('user.chat.messages.delete', $activeUser->id));
    const sendTextUrl = @json(route('user.chat.send', $activeUser->id));
    const readAllUrl = @json(route('user.chat.read-all', $activeUser->id));
    const pollMessagesUrl = @json(route('user.chat.messages.poll', $activeUser->id));
    const starredUrl = @json(route('user.chat.starred', $activeUser->id));
    const starMessagesUrl = @json(route('user.chat.messages.star', $activeUser->id));
    const blockUserUrl = @json(route('user.blocked.block', $activeUser->id));
    const profileUrl = @json(route('user.profile.view', $activeUser->id));
    const partnerName = @json(Str::limit($activeUser->name, 28));
    const partnerInitial = @json(strtoupper(\Illuminate\Support\Str::substr($activeUser->name ?? 'U', 0, 1)));
    const myUserId = {{ (int) auth()->id() }};
    const unsendMaxHours = 48;
    let pollTimer = null;
    let isPolling = false;
    let lastMessageId = Array.from(document.querySelectorAll('#waChatScroll .wa-bubble-wrap[data-msg-id]'))
        .map(el => parseInt(el.getAttribute('data-msg-id') || '0', 10))
        .filter(n => Number.isFinite(n) && n > 0)
        .reduce((max, n) => Math.max(max, n), 0);

    const waMenuWrap = document.getElementById('waMenuWrap');
    const waBtnMore = document.getElementById('waBtnMore');
    const waToast = document.getElementById('waToast');
    const waMsgMenu = document.getElementById('waMsgMenu');
    let waToastTimer = null;
    let activeMsgWrap = null;

    function showToast(msg) {
        if (!waToast) return;
        waToast.textContent = msg;
        waToast.classList.add('is-on');
        clearTimeout(waToastTimer);
        waToastTimer = setTimeout(() => waToast.classList.remove('is-on'), 2200);
    }

    function escapeHtml(value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    function currentDayKey() {
        const lastWrap = document.querySelector('#waChatScroll .wa-bubble-wrap:last-of-type');
        return lastWrap ? (lastWrap.getAttribute('data-day-key') || '') : '';
    }

    function sidebarPreviewFromMessage(msg) {
        if (!msg) return 'Tap to chat';
        if (msg.revoked) {
            return msg.is_out ? 'You deleted this message' : 'This message was deleted';
        }
        if (msg.is_image) {
            return msg.is_out ? 'You: [Image]' : '[Image]';
        }
        if (msg.is_audio) {
            return msg.is_out ? 'You: [Voice message]' : '[Voice message]';
        }
        const text = String(msg.text || '').trim() || 'Message';
        return msg.is_out ? ('You: ' + text) : text;
    }

    function updateSidebarForMessage(msg) {
        const row = document.querySelector('.wa-user-row[data-user-id="{{ (int) $activeUser->id }}"]');
        if (!row || !msg) return;

        const previewEl = row.querySelector('.wa-user-row-preview');
        if (previewEl) {
            previewEl.textContent = sidebarPreviewFromMessage(msg);
        }

        const timeWrap = row.querySelector('.wa-user-row-top');
        if (timeWrap) {
            let timeEl = row.querySelector('.wa-user-row-time');
            if (!timeEl) {
                timeEl = document.createElement('span');
                timeEl.className = 'wa-user-row-time';
                timeWrap.appendChild(timeEl);
            }
            timeEl.textContent = String(msg.time_label || '');
        }

        // Keep latest conversation at top like WhatsApp.
        const list = document.getElementById('waUserList');
        if (list) {
            list.prepend(row);
        }
    }

    function appendIncomingMessage(msg) {
        if (!scrollEl || !msg || !msg.id) return;
        if (document.querySelector('.wa-bubble-wrap[data-msg-id="' + msg.id + '"]')) return;

        const nearBottom = (scrollEl.scrollHeight - scrollEl.scrollTop - scrollEl.clientHeight) < 120;
        const dayKey = String(msg.day_key || '');
        if (dayKey && currentDayKey() !== dayKey) {
            scrollEl.insertAdjacentHTML('beforeend', '<div class="wa-day-sep"><span>' + escapeHtml(msg.day_label || dayKey) + '</span></div>');
        }

        const wrapClass = msg.is_out ? 'out' : 'in';
        let bodyHtml = '';
        if (msg.revoked) {
            bodyHtml = '<div class="wa-revoked">' + (msg.is_out ? 'You deleted this message' : 'This message was deleted') + '</div>';
        } else if (msg.media_url && msg.is_image) {
            bodyHtml = '<img src="' + escapeHtml(msg.media_url) + '" alt="" class="chat-img">';
        } else if (msg.media_url && msg.is_audio) {
            bodyHtml = '<audio controls preload="metadata" src="' + escapeHtml(msg.media_url) + '"></audio>';
        } else {
            bodyHtml = escapeHtml(msg.text || '');
        }

        const tickHtml = msg.is_out && !msg.revoked
            ? (msg.read_at
                ? '<span class="ticks read" title="Read"><svg width="20" height="12" viewBox="0 0 20 12" aria-hidden="true"><path d="M2.5 6.2L5.8 9.5L9.5 3" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.5 6.2L10.8 9.5L17.5 2" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"/></svg></span>'
                : '<span class="ticks sent" title="Delivered"><svg width="14" height="12" viewBox="0 0 14 12" aria-hidden="true"><path d="M2.5 6.2L5.8 9.5L11.5 2.5" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"/></svg></span>')
            : '';

        const starHtml = msg.starred ? '<span class="wa-star-icon" title="Starred">★</span>' : '';

        const peerAvatarHtml = wrapClass === 'in'
            ? '<a href="' + escapeHtml(profileUrl) + '" class="wa-peer-avatar wa-avatar wa-avatar--sm" title="' + escapeHtml(partnerName) + '">' + escapeHtml(partnerInitial) + '</a>'
            : '';
        const html = '<div class="wa-bubble-wrap ' + wrapClass + '" data-msg-id="' + Number(msg.id) + '" data-sender-id="' + Number(msg.sender_id || 0) + '" data-created="' + escapeHtml(msg.created_iso || '') + '" data-day-key="' + escapeHtml(dayKey) + '" data-revoked="' + (msg.revoked ? '1' : '0') + '" data-starred="' + (msg.starred ? '1' : '0') + '" data-copy-text="' + escapeHtml(msg.copy_text || '') + '" data-search-text="' + escapeHtml(msg.search_text || '') + '">' + peerAvatarHtml + '<div class="wa-bubble-cluster"><label class="wa-msg-check"><input type="checkbox" class="wa-msg-cb" value="' + Number(msg.id) + '"></label><div class="wa-bubble ' + wrapClass + '">' + bodyHtml + '<div class="wa-bubble-footer">' + starHtml + '<span>' + escapeHtml(msg.time_label || '') + '</span>' + tickHtml + '</div></div></div></div>';
        scrollEl.insertAdjacentHTML('beforeend', html);
        updateSidebarForMessage(msg);

        if (nearBottom || msg.is_out) {
            scrollEl.scrollTop = scrollEl.scrollHeight;
        }
        lastMessageId = Math.max(lastMessageId, Number(msg.id) || 0);

        // Active conversation is on-screen, so incoming messages here are immediately "seen".
        if (!msg.is_out) {
            setRowUnread({{ (int) $activeUser->id }}, 0);
            syncGlobalUnreadUi();
            applyListFilters();
        }
    }

    function markOutgoingMessagesAsRead(readUpToId) {
        const upto = Number(readUpToId || 0);
        if (!Number.isFinite(upto) || upto <= 0) return;
        const readTickHtml = '<svg width="20" height="12" viewBox="0 0 20 12" aria-hidden="true"><path d="M2.5 6.2L5.8 9.5L9.5 3" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"/><path d="M7.5 6.2L10.8 9.5L17.5 2" fill="none" stroke="currentColor" stroke-width="1.85" stroke-linecap="round" stroke-linejoin="round"/></svg>';
        document.querySelectorAll('#waChatScroll .wa-bubble-wrap.out[data-msg-id]').forEach(function (wrap) {
            const id = Number(wrap.getAttribute('data-msg-id') || 0);
            if (!Number.isFinite(id) || id <= 0 || id > upto) return;
            const tick = wrap.querySelector('.ticks');
            if (!tick) return;
            if (tick.classList.contains('read')) return;
            tick.classList.remove('sent');
            tick.classList.add('read');
            tick.setAttribute('title', 'Read');
            tick.innerHTML = readTickHtml;
        });
    }

    async function pollMessages() {
        if (isPolling || document.hidden) return;
        isPolling = true;
        try {
            const url = pollMessagesUrl + '?after_id=' + encodeURIComponent(String(lastMessageId || 0));
            const r = await fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
            const data = await r.json().catch(() => ({}));
            if (!r.ok || !data.success) return;
            const rows = Array.isArray(data.messages) ? data.messages : [];
            rows.forEach(appendIncomingMessage);
            markOutgoingMessagesAsRead(data.read_up_to_id);
            filterMsgsInChat();
            if (Number.isFinite(Number(data.last_id))) {
                lastMessageId = Math.max(lastMessageId, Number(data.last_id));
            }
        } catch (e) {
            // Silent fail; next interval will retry.
        } finally {
            isPolling = false;
        }
    }

    function startPolling() {
        if (pollTimer) clearInterval(pollTimer);
        pollTimer = setInterval(pollMessages, 1000);
        pollMessages();
    }

    const waTextForm = document.getElementById('waTextForm');
    const waTextInput = document.getElementById('waTextInput');
    if (waTextForm && waTextInput) {
        waTextForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const text = waTextInput.value.trim();
            if (!text) return;
            waTextInput.disabled = true;
            try {
                const fd = new FormData();
                fd.append('_token', csrf);
                fd.append('message', text);
                const r = await fetch(sendTextUrl, {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    credentials: 'same-origin'
                });
                const data = await r.json().catch(() => ({}));
                if (r.ok && data.success && data.message) {
                    appendIncomingMessage(data.message);
                    waTextInput.value = '';
                    syncComposer();
                    pollMessages();
                } else {
                    showToast(data.message || 'Message send failed');
                }
            } catch (err) {
                showToast('Message send failed');
            } finally {
                waTextInput.disabled = false;
                waTextInput.focus();
            }
        });
    }

    function closeChatMenu() {
        waMenuWrap?.classList.remove('is-open');
        if (waBtnMore) waBtnMore.setAttribute('aria-expanded', 'false');
    }
    function closeMsgMenu() {
        if (!waMsgMenu) return;
        waMsgMenu.classList.remove('is-on');
        waMsgMenu.setAttribute('aria-hidden', 'true');
        activeMsgWrap = null;
    }

    waBtnMore?.addEventListener('click', function (e) {
        e.stopPropagation();
        const open = !waMenuWrap?.classList.contains('is-open');
        if (open) waMenuWrap?.classList.add('is-open');
        else waMenuWrap?.classList.remove('is-open');
        waBtnMore?.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    document.addEventListener('click', function () {
        closeChatMenu();
        closeMsgMenu();
    });
    waMenuWrap?.addEventListener('click', function (e) { e.stopPropagation(); });
    waMsgMenu?.addEventListener('click', function (e) { e.stopPropagation(); });

    function openModal(id) {
        const m = document.getElementById(id);
        if (m) {
            m.classList.add('is-on');
            m.setAttribute('aria-hidden', 'false');
        }
        closeChatMenu();
    }
    function closeModals() {
        document.querySelectorAll('.wa-modal.is-on').forEach(m => {
            m.classList.remove('is-on');
            m.setAttribute('aria-hidden', 'true');
        });
    }
    document.querySelectorAll('[data-close-modal]').forEach(el => {
        el.addEventListener('click', closeModals);
    });

    document.querySelectorAll('#waChatMoreMenu [data-menu]').forEach(btn => {
        btn.addEventListener('click', async function () {
            const act = btn.getAttribute('data-menu');
            if (act === 'profile') {
                window.location.href = profileUrl;
                return;
            }
            if (act === 'select') {
                setSelectMode(true);
                showToast('Select messages');
                return;
            }
            if (act === 'search') {
                waBtnSearchMsgs?.click();
                return;
            }
            if (act === 'starred') {
                openModal('waModalStarred');
                const body = document.getElementById('waStarredBody');
                if (body) body.innerHTML = '<p style="color:var(--wa-muted);font-size:14px;">Loading…</p>';
                try {
                    const r = await fetch(starredUrl, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
                    const data = await r.json().catch(() => ({}));
                    if (!r.ok || !data.success) {
                        body.innerHTML = '<p style="color:#b91c1c;">Could not load starred messages.</p>';
                        return;
                    }
                    const list = data.messages || [];
                    if (!list.length) {
                        body.innerHTML = '<p style="color:var(--wa-muted);font-size:14px;">No starred messages in this chat.</p>';
                        return;
                    }
                    body.innerHTML = list.map(row => {
                        const who = row.is_out ? 'You' : partnerName;
                        const esc = s => String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/"/g, '&quot;');
                        const prev = row.preview || (row.revoked ? '[Deleted message]' : '');
                        return '<div class="wa-starred-row"><div class="wa-starred-meta">' + esc(who) + ' · ' + esc(row.date || '') + ' ' + esc(row.time || '') + '</div><div>' + esc(prev) + '</div></div>';
                    }).join('');
                } catch (e) {
                    body.innerHTML = '<p style="color:#b91c1c;">Could not load starred messages.</p>';
                }
                return;
            }
            if (act === 'read-all') {
                const fd = new FormData();
                fd.append('_token', csrf);
                try {
                    const r = await fetch(readAllUrl, {
                        method: 'POST',
                        body: fd,
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        credentials: 'same-origin'
                    });
                    const data = await r.json().catch(() => ({}));
                    if (r.ok && data.success) showToast('Marked as read');
                    else showToast('Could not update read status');
                } catch (e) {
                    showToast('Could not update read status');
                }
                return;
            }
            if (act === 'settings') {
                openModal('waModalSettings');
                return;
            }
            if (act === 'block') {
                if (!confirm('Block this user? You can unblock later from Blocked users.')) return;
                const fd = new FormData();
                fd.append('_token', csrf);
                try {
                    const r = await fetch(blockUserUrl, {
                        method: 'POST',
                        body: fd,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        credentials: 'same-origin'
                    });
                    if (r.ok) {
                        window.location.href = @json(route('user.blocked.index'));
                        return;
                    }
                    showToast('Could not block user');
                } catch (e) {
                    showToast('Could not block user');
                }
                return;
            }
            if (act === 'delete') {
                const hasSelected = getCheckedWrappers().length > 0;
                if (!hasSelected) {
                    setSelectMode(true);
                    showToast('Select messages then tap Delete messages');
                    return;
                }
                openModal('waModalDeleteChoices');
                const btnEvery = document.getElementById('waModalDeleteEveryone');
                if (btnEvery) btnEvery.disabled = !!waBtnDeleteEveryone?.disabled;
            }
        });
    });

    function getCheckedWrappers() {
        const cbs = document.querySelectorAll('.wa-msg-cb:checked');
        return Array.from(cbs).map(cb => cb.closest('.wa-bubble-wrap')).filter(Boolean);
    }
    function selectOnlyWrap(wrap) {
        if (!wrap) return;
        setSelectMode(true);
        document.querySelectorAll('.wa-msg-cb').forEach(cb => { cb.checked = false; });
        const cb = wrap.querySelector('.wa-msg-cb');
        if (cb) cb.checked = true;
        refreshSelectUi();
    }
    function openMsgMenuForWrap(wrap, x, y) {
        if (!waMsgMenu || !wrap) return;
        activeMsgWrap = wrap;
        waMsgMenu.classList.add('is-on');
        waMsgMenu.setAttribute('aria-hidden', 'false');
        // First place it near click point, then clamp using actual menu size.
        waMsgMenu.style.left = (x || 40) + 'px';
        waMsgMenu.style.top = (y || 120) + 'px';
        const rect = waMsgMenu.getBoundingClientRect();
        const margin = 10;
        const left = Math.max(margin, Math.min((x || 40), window.innerWidth - rect.width - margin));
        const top = Math.max(70, Math.min((y || 120), window.innerHeight - rect.height - margin));
        waMsgMenu.style.left = left + 'px';
        waMsgMenu.style.top = top + 'px';
    }

    function refreshSelectUi() {
        const n = document.querySelectorAll('.wa-msg-cb:checked').length;
        if (waSelectCount) waSelectCount.textContent = n === 1 ? '1 selected' : (n + ' selected');

        const wraps = getCheckedWrappers();
        let copyOk = false;
        for (const w of wraps) {
            const t = (w.dataset.copyText || '').trim();
            if (t) copyOk = true;
        }
        if (waBtnCopy) waBtnCopy.disabled = !copyOk;

        let everyoneOk = n > 0;
        const cutoff = Date.now() - unsendMaxHours * 3600 * 1000;
        for (const w of wraps) {
            if (parseInt(w.dataset.senderId, 10) !== myUserId) everyoneOk = false;
            if (w.dataset.revoked === '1') everyoneOk = false;
            const created = Date.parse(w.dataset.created || '');
            if (!Number.isFinite(created) || created < cutoff) everyoneOk = false;
        }
        if (waBtnDeleteEveryone) waBtnDeleteEveryone.disabled = !everyoneOk;

        let anyStarred = false;
        let anyUnstarred = false;
        for (const w of wraps) {
            if (w.dataset.starred === '1') anyStarred = true;
            else anyUnstarred = true;
        }
        if (waBtnStar) waBtnStar.disabled = n === 0 || !anyUnstarred;
        if (waBtnUnstar) waBtnUnstar.disabled = n === 0 || !anyStarred;
    }

    function setSelectMode(on) {
        if (!waMain) return;
        waMain.classList.toggle('wa-select-on', on);
        if (!on) {
            document.querySelectorAll('.wa-msg-cb').forEach(cb => { cb.checked = false; });
        }
        refreshSelectUi();
    }
    waBtnCancelSelect?.addEventListener('click', function () { setSelectMode(false); });

    waSelectAll?.addEventListener('click', function () {
        document.querySelectorAll('.wa-msg-cb').forEach(cb => { cb.checked = true; });
        refreshSelectUi();
    });
    waSelectNone?.addEventListener('click', function () {
        document.querySelectorAll('.wa-msg-cb').forEach(cb => { cb.checked = false; });
        refreshSelectUi();
    });

    document.getElementById('waChatScroll')?.addEventListener('change', function (e) {
        if (e.target.classList?.contains('wa-msg-cb')) refreshSelectUi();
    });

    document.getElementById('waChatScroll')?.addEventListener('click', function (e) {
        if (!waMain?.classList.contains('wa-select-on')) return;
        const checkLabel = e.target.closest('.wa-msg-check');
        const input = e.target.closest('input');
        if (checkLabel || input) return;
        const wrap = e.target.closest('.wa-bubble-wrap');
        if (!wrap) return;
        const cb = wrap.querySelector('.wa-msg-cb');
        if (cb) {
            cb.checked = !cb.checked;
            refreshSelectUi();
        }
    });
    document.getElementById('waChatScroll')?.addEventListener('click', function (e) {
        if (waMain?.classList.contains('wa-select-on')) return;
        const wrap = e.target.closest('.wa-bubble-wrap');
        if (!wrap) return;
        if (e.target.closest('audio') || e.target.closest('.wa-msg-check')) return;
        e.preventDefault();
        e.stopPropagation();
        closeChatMenu();
        openMsgMenuForWrap(wrap, e.clientX + 8, e.clientY + 8);
    });

    waMsgMenu?.querySelectorAll('[data-react]').forEach(btn => {
        btn.addEventListener('click', function () {
            const r = btn.getAttribute('data-react');
            if (r === '+') showToast('More reactions coming soon');
            else showToast('Reacted ' + r);
            closeMsgMenu();
        });
    });
    waMsgMenu?.querySelectorAll('[data-act]').forEach(btn => {
        btn.addEventListener('click', async function () {
            if (!activeMsgWrap) return;
            const act = btn.getAttribute('data-act');
            const text = (activeMsgWrap.dataset.copyText || '').trim();
            const id = parseInt(activeMsgWrap.dataset.msgId, 10);
            if (act === 'reply') {
                const inp = document.getElementById('waTextInput');
                if (inp) {
                    inp.value = '@reply ' + (text ? (text.slice(0, 80) + ' ') : '');
                    inp.focus();
                    syncComposer();
                }
                showToast('Reply mode');
                closeMsgMenu();
                return;
            }
            if (act === 'copy') {
                if (!text) { showToast('No text to copy'); closeMsgMenu(); return; }
                try {
                    await navigator.clipboard.writeText(text);
                } catch (err) {}
                showToast('Copied');
                closeMsgMenu();
                return;
            }
            if (act === 'react') { showToast('Tap emoji row to react'); closeMsgMenu(); return; }
            if (act === 'forward' || act === 'pin') { showToast('This option will be added next'); closeMsgMenu(); return; }
            if (act === 'star') {
                if (Number.isFinite(id)) {
                    selectOnlyWrap(activeMsgWrap);
                    postStar(activeMsgWrap.dataset.starred === '1' ? 'unstar' : 'star');
                }
                closeMsgMenu();
                return;
            }
            if (act === 'delete') {
                selectOnlyWrap(activeMsgWrap);
                openModal('waModalDeleteChoices');
                const btnEvery = document.getElementById('waModalDeleteEveryone');
                if (btnEvery) btnEvery.disabled = !!waBtnDeleteEveryone?.disabled;
                closeMsgMenu();
            }
        });
    });

    waBtnCopy?.addEventListener('click', async function () {
        const lines = getCheckedWrappers()
            .map(w => (w.dataset.copyText || '').trim())
            .filter(Boolean);
        if (!lines.length) return;
        const text = lines.join('\n');
        try {
            await navigator.clipboard.writeText(text);
            showToast('Copied');
        } catch (err) {
            const ta = document.createElement('textarea');
            ta.value = text;
            document.body.appendChild(ta);
            ta.select();
            try { document.execCommand('copy'); } catch (e2) {}
            ta.remove();
            showToast('Copied');
        }
    });

    async function postStar(action) {
        const ids = getCheckedWrappers().map(w => parseInt(w.dataset.msgId, 10)).filter(Number.isFinite);
        if (!ids.length) return;
        const fd = new FormData();
        fd.append('_token', csrf);
        fd.append('action', action);
        ids.forEach(id => fd.append('ids[]', String(id)));
        try {
            const r = await fetch(starMessagesUrl, {
                method: 'POST',
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                credentials: 'same-origin'
            });
            const data = await r.json().catch(() => ({}));
            if (r.ok && data.success) {
                window.location.href = data.redirect || window.location.href;
                return;
            }
            alert(data.message || 'Could not update stars.');
        } catch (e) {
            alert('Could not update stars.');
        }
    }

    waBtnStar?.addEventListener('click', function () {
        if (waBtnStar.disabled) return;
        postStar('star');
    });
    waBtnUnstar?.addEventListener('click', function () {
        if (waBtnUnstar.disabled) return;
        postStar('unstar');
    });

    async function postDelete(scope) {
        const ids = getCheckedWrappers().map(w => parseInt(w.dataset.msgId, 10)).filter(Number.isFinite);
        if (!ids.length) return;
        const fd = new FormData();
        fd.append('_token', csrf);
        fd.append('scope', scope);
        ids.forEach(id => fd.append('ids[]', String(id)));
        try {
            const r = await fetch(deleteMessagesUrl, {
                method: 'POST',
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                credentials: 'same-origin'
            });
            const data = await r.json().catch(() => ({}));
            if (r.ok && data.success) {
                window.location.href = data.redirect || window.location.href;
                return;
            }
            alert(data.message || 'Could not update messages.');
        } catch (e) {
            alert('Could not update messages.');
        }
    }

    waBtnDeleteMe?.addEventListener('click', function () {
        if (!getCheckedWrappers().length) return;
        if (!confirm('Remove selected messages from this chat on your side only?')) return;
        postDelete('me');
    });
    waBtnDeleteEveryone?.addEventListener('click', function () {
        if (waBtnDeleteEveryone.disabled) return;
        if (!confirm('Unsend these messages? They will disappear for both of you (if still allowed).')) return;
        postDelete('everyone');
    });
    document.getElementById('waModalDeleteMe')?.addEventListener('click', function () {
        closeModals();
        postDelete('me');
    });
    document.getElementById('waModalDeleteEveryone')?.addEventListener('click', function () {
        if (waBtnDeleteEveryone?.disabled) {
            showToast('Some selected messages cannot be deleted for everyone');
            return;
        }
        closeModals();
        postDelete('everyone');
    });

    document.addEventListener('visibilitychange', function () {
        if (!document.hidden) {
            pollMessages();
        }
    });
    startPolling();
})();
</script>
@endif
@endsection
