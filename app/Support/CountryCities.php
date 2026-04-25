<?php

namespace App\Support;

/**
 * Single source of truth for registration country dropdown and profile city dropdowns.
 */
final class CountryCities
{
    /**
     * @return array<string, list<string>>
     */
    private static function map(): array
    {
        static $map = null;
        if ($map !== null) {
            return $map;
        }

        $map = [
            'Afghanistan' => ['Kabul', 'Kandahar', 'Herat', 'Mazar-i-Sharif', 'Jalalabad', 'Kunduz', 'Other'],
            'Albania' => ['Tirana', 'Durrës', 'Vlorë', 'Shkodër', 'Fier', 'Other'],
            'Algeria' => ['Algiers', 'Oran', 'Constantine', 'Annaba', 'Blida', 'Other'],
            'Argentina' => ['Buenos Aires', 'Córdoba', 'Rosario', 'Mendoza', 'La Plata', 'Other'],
            'Armenia' => ['Yerevan', 'Gyumri', 'Vanadzor', 'Other'],
            'Australia' => ['Sydney', 'Melbourne', 'Brisbane', 'Perth', 'Adelaide', 'Canberra', 'Gold Coast', 'Other'],
            'Austria' => ['Vienna', 'Graz', 'Linz', 'Salzburg', 'Innsbruck', 'Other'],
            'Azerbaijan' => ['Baku', 'Ganja', 'Sumqayit', 'Other'],
            'Bahrain' => ['Manama', 'Riffa', 'Muharraq', 'Hamad Town', 'Other'],
            'Bangladesh' => ['Dhaka', 'Chittagong', 'Khulna', 'Rajshahi', 'Sylhet', 'Barisal', 'Rangpur', 'Other'],
            'Belgium' => ['Brussels', 'Antwerp', 'Ghent', 'Charleroi', 'Liège', 'Bruges', 'Other'],
            'Bhutan' => ['Thimphu', 'Phuntsholing', 'Paro', 'Other'],
            'Bolivia' => ['La Paz', 'Santa Cruz', 'Cochabamba', 'Sucre', 'Other'],
            'Bosnia and Herzegovina' => ['Sarajevo', 'Banja Luka', 'Tuzla', 'Mostar', 'Other'],
            'Brazil' => ['São Paulo', 'Rio de Janeiro', 'Brasília', 'Salvador', 'Fortaleza', 'Belo Horizonte', 'Other'],
            'Bulgaria' => ['Sofia', 'Plovdiv', 'Varna', 'Burgas', 'Other'],
            'Cambodia' => ['Phnom Penh', 'Siem Reap', 'Battambang', 'Other'],
            'Cameroon' => ['Douala', 'Yaoundé', 'Garoua', 'Other'],
            'Canada' => ['Toronto', 'Montreal', 'Vancouver', 'Calgary', 'Edmonton', 'Ottawa', 'Winnipeg', 'Other'],
            'Chile' => ['Santiago', 'Valparaíso', 'Concepción', 'Other'],
            'China' => ['Beijing', 'Shanghai', 'Guangzhou', 'Shenzhen', 'Chengdu', 'Hong Kong', 'Other'],
            'Colombia' => ['Bogotá', 'Medellín', 'Cali', 'Barranquilla', 'Cartagena', 'Other'],
            'Croatia' => ['Zagreb', 'Split', 'Rijeka', 'Osijek', 'Other'],
            'Cyprus' => ['Nicosia', 'Limassol', 'Larnaca', 'Other'],
            'Czechia' => ['Prague', 'Brno', 'Ostrava', 'Plzeň', 'Other'],
            'Denmark' => ['Copenhagen', 'Aarhus', 'Odense', 'Aalborg', 'Other'],
            'Dominican Republic' => ['Santo Domingo', 'Santiago', 'La Vega', 'Other'],
            'Ecuador' => ['Quito', 'Guayaquil', 'Cuenca', 'Other'],
            'Egypt' => ['Cairo', 'Alexandria', 'Giza', 'Shubra El Kheima', 'Port Said', 'Luxor', 'Other'],
            'Estonia' => ['Tallinn', 'Tartu', 'Narva', 'Other'],
            'Ethiopia' => ['Addis Ababa', 'Dire Dawa', 'Mekelle', 'Other'],
            'Finland' => ['Helsinki', 'Espoo', 'Tampere', 'Vantaa', 'Other'],
            'France' => ['Paris', 'Marseille', 'Lyon', 'Toulouse', 'Nice', 'Nantes', 'Other'],
            'Georgia' => ['Tbilisi', 'Batumi', 'Kutaisi', 'Other'],
            'Germany' => ['Berlin', 'Hamburg', 'Munich', 'Cologne', 'Frankfurt', 'Stuttgart', 'Düsseldorf', 'Other'],
            'Ghana' => ['Accra', 'Kumasi', 'Tamale', 'Other'],
            'Greece' => ['Athens', 'Thessaloniki', 'Patras', 'Heraklion', 'Other'],
            'Hong Kong' => ['Hong Kong Island', 'Kowloon', 'New Territories', 'Other'],
            'Hungary' => ['Budapest', 'Debrecen', 'Szeged', 'Other'],
            'Iceland' => ['Reykjavík', 'Kópavogur', 'Other'],
            'India' => ['Mumbai', 'Delhi', 'Bangalore', 'Hyderabad', 'Chennai', 'Kolkata', 'Pune', 'Ahmedabad', 'Jaipur', 'Surat', 'Lucknow', 'Other'],
            'Indonesia' => ['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Other'],
            'Iran' => ['Tehran', 'Mashhad', 'Isfahan', 'Karaj', 'Shiraz', 'Tabriz', 'Other'],
            'Iraq' => ['Baghdad', 'Basra', 'Mosul', 'Erbil', 'Other'],
            'Ireland' => ['Dublin', 'Cork', 'Limerick', 'Galway', 'Other'],
            'Israel' => ['Jerusalem', 'Tel Aviv', 'Haifa', 'Rishon LeZion', 'Other'],
            'Italy' => ['Rome', 'Milan', 'Naples', 'Turin', 'Palermo', 'Florence', 'Other'],
            'Jamaica' => ['Kingston', 'Montego Bay', 'Spanish Town', 'Other'],
            'Japan' => ['Tokyo', 'Yokohama', 'Osaka', 'Nagoya', 'Kyoto', 'Fukuoka', 'Other'],
            'Jordan' => ['Amman', 'Zarqa', 'Irbid', 'Aqaba', 'Other'],
            'Kazakhstan' => ['Almaty', 'Astana', 'Shymkent', 'Other'],
            'Kenya' => ['Nairobi', 'Mombasa', 'Kisumu', 'Nakuru', 'Other'],
            'Kuwait' => ['Kuwait City', 'Al Ahmadi', 'Hawalli', 'Other'],
            'Kyrgyzstan' => ['Bishkek', 'Osh', 'Other'],
            'Laos' => ['Vientiane', 'Pakse', 'Luang Prabang', 'Other'],
            'Latvia' => ['Riga', 'Daugavpils', 'Other'],
            'Lebanon' => ['Beirut', 'Tripoli', 'Sidon', 'Other'],
            'Libya' => ['Tripoli', 'Benghazi', 'Misrata', 'Other'],
            'Lithuania' => ['Vilnius', 'Kaunas', 'Klaipėda', 'Other'],
            'Luxembourg' => ['Luxembourg City', 'Esch-sur-Alzette', 'Other'],
            'Madagascar' => ['Antananarivo', 'Toamasina', 'Other'],
            'Malaysia' => ['Kuala Lumpur', 'George Town', 'Johor Bahru', 'Ipoh', 'Malacca City', 'Kota Kinabalu', 'Other'],
            'Maldives' => ['Malé', 'Addu City', 'Other'],
            'Malta' => ['Valletta', 'Birkirkara', 'Other'],
            'Mexico' => ['Mexico City', 'Guadalajara', 'Monterrey', 'Puebla', 'Tijuana', 'Other'],
            'Moldova' => ['Chișinău', 'Tiraspol', 'Bălți', 'Other'],
            'Mongolia' => ['Ulaanbaatar', 'Erdenet', 'Other'],
            'Montenegro' => ['Podgorica', 'Nikšić', 'Other'],
            'Morocco' => ['Casablanca', 'Rabat', 'Fez', 'Marrakesh', 'Tangier', 'Other'],
            'Myanmar' => ['Yangon', 'Mandalay', 'Naypyidaw', 'Other'],
            'Nepal' => ['Kathmandu', 'Pokhara', 'Lalitpur', 'Bharatpur', 'Other'],
            'Netherlands' => ['Amsterdam', 'Rotterdam', 'The Hague', 'Utrecht', 'Eindhoven', 'Other'],
            'New Zealand' => ['Auckland', 'Wellington', 'Christchurch', 'Hamilton', 'Other'],
            'Nigeria' => ['Lagos', 'Abuja', 'Kano', 'Ibadan', 'Port Harcourt', 'Benin City', 'Other'],
            'Norway' => ['Oslo', 'Bergen', 'Trondheim', 'Stavanger', 'Other'],
            'Oman' => ['Muscat', 'Salalah', 'Sohar', 'Other'],
            'Pakistan' => [
                'Karachi', 'Lahore', 'Islamabad', 'Rawalpindi', 'Faisalabad', 'Multan',
                'Peshawar', 'Quetta', 'Sialkot', 'Gujranwala', 'Hyderabad', 'Bahawalpur',
                'Sargodha', 'Sukkur', 'Larkana', 'Sheikhupura', 'Mardan', 'Gujrat', 'Kasur',
                'Rahim Yar Khan', 'Sahiwal', 'Okara', 'Wah Cantonment', 'Dera Ghazi Khan',
                'Mirpur Khas', 'Nawabshah', 'Abbottabad', 'Mingora', 'Jhelum', 'Other',
            ],
            'Panama' => ['Panama City', 'San Miguelito', 'Other'],
            'Paraguay' => ['Asunción', 'Ciudad del Este', 'Other'],
            'Peru' => ['Lima', 'Arequipa', 'Trujillo', 'Other'],
            'Philippines' => ['Manila', 'Quezon City', 'Davao', 'Cebu City', 'Zamboanga City', 'Other'],
            'Poland' => ['Warsaw', 'Kraków', 'Łódź', 'Wrocław', 'Poznań', 'Gdańsk', 'Other'],
            'Portugal' => ['Lisbon', 'Porto', 'Braga', 'Other'],
            'Qatar' => ['Doha', 'Al Rayyan', 'Al Wakrah', 'Other'],
            'Romania' => ['Bucharest', 'Cluj-Napoca', 'Timișoara', 'Iași', 'Other'],
            'Russia' => ['Moscow', 'Saint Petersburg', 'Novosibirsk', 'Yekaterinburg', 'Kazan', 'Other'],
            'Saudi Arabia' => ['Riyadh', 'Jeddah', 'Mecca', 'Medina', 'Dammam', 'Khobar', 'Taif', 'Other'],
            'Serbia' => ['Belgrade', 'Novi Sad', 'Niš', 'Other'],
            'Singapore' => ['Singapore', 'Other'],
            'Slovakia' => ['Bratislava', 'Košice', 'Other'],
            'Slovenia' => ['Ljubljana', 'Maribor', 'Other'],
            'South Africa' => ['Johannesburg', 'Cape Town', 'Durban', 'Pretoria', 'Port Elizabeth', 'Other'],
            'South Korea' => ['Seoul', 'Busan', 'Incheon', 'Daegu', 'Daejeon', 'Other'],
            'Spain' => ['Madrid', 'Barcelona', 'Valencia', 'Seville', 'Zaragoza', 'Málaga', 'Other'],
            'Sri Lanka' => ['Colombo', 'Dehiwala-Mount Lavinia', 'Moratuwa', 'Kandy', 'Jaffna', 'Other'],
            'Sudan' => ['Khartoum', 'Omdurman', 'Other'],
            'Sweden' => ['Stockholm', 'Gothenburg', 'Malmö', 'Uppsala', 'Other'],
            'Switzerland' => ['Zurich', 'Geneva', 'Basel', 'Bern', 'Lausanne', 'Other'],
            'Syria' => ['Damascus', 'Aleppo', 'Homs', 'Other'],
            'Taiwan' => ['Taipei', 'Kaohsiung', 'Taichung', 'Tainan', 'Other'],
            'Tajikistan' => ['Dushanbe', 'Khujand', 'Other'],
            'Tanzania' => ['Dar es Salaam', 'Mwanza', 'Dodoma', 'Arusha', 'Other'],
            'Thailand' => ['Bangkok', 'Chiang Mai', 'Pattaya', 'Phuket', 'Hat Yai', 'Other'],
            'Tunisia' => ['Tunis', 'Sfax', 'Sousse', 'Other'],
            'Turkey' => ['Istanbul', 'Ankara', 'İzmir', 'Antalya', 'Bursa', 'Adana', 'Gaziantep', 'Other'],
            'Uganda' => ['Kampala', 'Gulu', 'Mbarara', 'Other'],
            'Ukraine' => ['Kyiv', 'Kharkiv', 'Odesa', 'Dnipro', 'Lviv', 'Other'],
            'United Arab Emirates' => ['Dubai', 'Abu Dhabi', 'Sharjah', 'Ajman', 'Ras Al Khaimah', 'Al Ain', 'Other'],
            'United Kingdom' => ['London', 'Birmingham', 'Manchester', 'Leeds', 'Glasgow', 'Liverpool', 'Edinburgh', 'Bristol', 'Cardiff', 'Belfast', 'Other'],
            'United States' => ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia', 'San Antonio', 'San Diego', 'Dallas', 'Miami', 'Other'],
            'Uruguay' => ['Montevideo', 'Salto', 'Other'],
            'Uzbekistan' => ['Tashkent', 'Samarkand', 'Other'],
            'Venezuela' => ['Caracas', 'Maracaibo', 'Valencia', 'Other'],
            'Vietnam' => ['Ho Chi Minh City', 'Hanoi', 'Da Nang', 'Hai Phong', 'Other'],
            'Yemen' => ['Sanaa', 'Aden', 'Taiz', 'Other'],
            'Zambia' => ['Lusaka', 'Kitwe', 'Ndola', 'Other'],
            'Zimbabwe' => ['Harare', 'Bulawayo', 'Other'],
        ];

        ksort($map);

        return $map;
    }

    /**
     * @return list<string>
     */
    public static function countries(): array
    {
        return array_keys(self::map());
    }

    /**
     * @return list<string>
     */
    public static function citiesFor(?string $country): array
    {
        if ($country === null || $country === '') {
            return ['Other'];
        }

        $map = self::map();
        if (isset($map[$country])) {
            return $map[$country];
        }

        return ['Other'];
    }

    public static function hasCountry(?string $country): bool
    {
        if ($country === null || $country === '') {
            return false;
        }

        return isset(self::map()[$country]);
    }

    public static function isValidCity(?string $country, ?string $city): bool
    {
        if ($city === null || trim($city) === '') {
            return false;
        }

        $cities = self::citiesFor($country);

        return in_array(trim($city), $cities, true);
    }
}
