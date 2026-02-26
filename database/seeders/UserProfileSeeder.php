<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserProfile;
use Faker\Factory as Faker;

class UserProfileSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        $sects = ['Sunni', 'Shia', 'Ahmadi', 'Nation of Islam', 'Ibadi', 'Just Muslim', 'Prefer not to say'];
        $educations = ['High School', 'Diploma / Vocational', "Bachelor's Degree", "Master's Degree", 'PhD / Doctorate', 'Islamic Education', 'Other'];
        $maritalStatuses = ['Never Married', 'Divorced', 'Widowed', 'Separated', 'Married (polygamy)'];
        $marriageIntentions = ['Seriously looking', 'Open to options', 'Not sure yet', 'Within 1 year', 'Within 2-3 years'];
        $religionPractices = ['Practising', 'Moderately Practising', 'Not Practising', 'Revert / New Muslim'];
        $bornMuslims = ['Yes', 'No', 'Prefer not to say'];
        $nationalities = ['Pakistani', 'British', 'American', 'Indian', 'Bangladeshi', 'Saudi Arabian', 'Emirati', 'Turkish', 'Egyptian', 'Malaysian', 'Indonesian', 'Canadian'];
        $cities = ['Karachi', 'Lahore', 'Islamabad', 'London', 'New York', 'Birmingham', 'Dubai', 'Kuala Lumpur', 'Toronto', 'Istanbul'];
        $ethnicities = ['Arab', 'South Asian', 'African', 'Black British', 'White / Caucasian', 'East Asian', 'Southeast Asian', 'Mixed', 'Other', 'Prefer not to say'];
        $interests = ['Acting', 'Anime', 'Art galleries', 'Board games', 'Creative writing', 'Design', 'DIY', 'Fashion', 'Film & Cinema', 'Photography', 'Painting', 'Music', 'Football', 'Cricket', 'Basketball', 'Gym', 'Running', 'Swimming', 'Cycling', 'Hiking', 'Martial Arts', 'Tennis', 'Cooking', 'Baking', 'Trying restaurants', 'Coffee', 'Travelling', 'Reading', 'Gardening', 'Volunteering', 'Technology', 'Gaming', 'Podcasts', 'Languages', 'Science', 'History', 'Islamic Studies'];
        $personalities = ['Introvert', 'Extrovert', 'Ambivert', 'Ambitious', 'Caring', 'Funny', 'Calm', 'Creative', 'Adventurous', 'Family-oriented', 'Intellectual', 'Spiritual', 'Hardworking', 'Romantic', 'Empathetic'];

        for ($i = 0; $i < 20; $i++) { // Change 20 to how many fake profiles you want
            UserProfile::create([
                'sect' => $faker->randomElement($sects),
                'profession' => $faker->jobTitle,
                'education' => $faker->randomElement($educations),
                'notifications_enabled' => $faker->boolean,
                'hide_from_contacts' => $faker->boolean,
                'nationality' => $faker->randomElement($nationalities),
                'grew_up' => $faker->randomElement($cities),
                'ethnicity' => implode(',', $faker->randomElements($ethnicities, rand(1,3))),
                'height_cm' => $faker->numberBetween(140, 220),
                'marital_status' => $faker->randomElement($maritalStatuses),
                'marriage_intentions' => $faker->randomElement($marriageIntentions),
                'religion_practice' => $faker->randomElement($religionPractices),
                'born_muslim' => $faker->randomElement($bornMuslims),
                'interests' => implode(',', $faker->randomElements($interests, rand(3,10))),
                'bio' => $faker->paragraph,
                'personality' => implode(',', $faker->randomElements($personalities, rand(2,5))),
            ]);
        }
    }
}
