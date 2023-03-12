<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = ['Marketing', 'Finance', 'Operations management', 'Human Resource', 'IT'];
        $skills = ["Adaptability", "Artistic sense", "Assertiveness", "Collaborative abilities", "Communication skills (including verbal and written!)", "Compassion", "Competitiveness", "Conflict or dispute resolution", "Creative thinking", "Crisis management skills", "Decision making skills", "Delegation skills", "Design sense", "Diplomacy", "Emotional regulation", "Empathy", "Enthusiasm", "Facilitating skills", "Flexibility", "Friendliness", "Humor", "Influence/persuasion skills", "Innovation", "Interpersonal skills", "Leadership skills", "Listening skills", "Management skills (both for time and people!)", "Mentoring/coaching skills", "Motivational skills", "Negotiating skills", "Networking skills", "Openness to feedback", "Patience", "Perceptiveness", "Perseverance/persistence", "Positivity", "Presenting/public speaking skills", "Problem solving and troubleshooting", "Research skills", "Resilience", "Scheduling skills", "Self assessment", "Self awareness", "Self confidence", "Strategic skills", "Stress management", "Supervision skills", "Teamwork/team player skills (also goes hand in hand with collaboration)", "Time management", "Work ethics"];

        //Create departments
        foreach ($departments as $department) {
            Department::create(['name' => $department]);
        }

        //Create skills
        foreach ($skills as $skill) {
            Skill::create(['name' => $skill]);
        }

        //Create user with skills and assigned department
        User::factory()->count(86)->create();

        User::all()->each(function ($user) {
            $user->skills()->attach(Skill::all()->random(5));
            $user->departments()->attach(Department::all()->random(1));
        });
    }
}
