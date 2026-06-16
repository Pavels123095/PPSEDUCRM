<?php

namespace Database\Seeders;

use App\Models\Applicant;
use App\Models\Classroom;
use App\Models\Contract;
use App\Models\Manager;
use App\Models\ScheduleSlot;
use App\Models\Student;
use App\Models\StudyGroup;
use App\Models\Teacher;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@ppseducrm.local'],
            ['name' => 'Администратор', 'password' => Hash::make('password')]
        );
        $admin->assignRole('admin');

        $managerUser = User::firstOrCreate(
            ['email' => 'manager@ppseducrm.local'],
            ['name' => 'Иванова Мария Петровна', 'password' => Hash::make('password')]
        );
        $managerUser->assignRole('manager');
        $manager = Manager::firstOrCreate(
            ['user_id' => $managerUser->id],
            ['phone' => '+7 (495) 123-45-67', 'department' => 'Приёмная комиссия']
        );

        $teacherUsers = [
            ['email' => 'teacher1@ppseducrm.local', 'name' => 'Смирнов Алексей Игоревич', 'disciplines' => ['Математика', 'Физика']],
            ['email' => 'teacher2@ppseducrm.local', 'name' => 'Кузнецова Елена Владимировна', 'disciplines' => ['Информатика']],
            ['email' => 'teacher3@ppseducrm.local', 'name' => 'Петров Дмитрий Сергеевич', 'disciplines' => ['Экономика']],
        ];

        $teachers = collect();
        foreach ($teacherUsers as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('password')]
            );
            $user->assignRole('teacher');
            $teachers->push(Teacher::firstOrCreate(
                ['user_id' => $user->id],
                ['hourly_rate' => 850, 'disciplines' => $data['disciplines']]
            ));
        }

        $group = StudyGroup::firstOrCreate(
            ['code' => 'ИС-101'],
            ['name' => 'ИС-101', 'course' => 1, 'specialty' => 'Информационные системы']
        );

        $studentNames = [
            ['email' => 'student1@ppseducrm.local', 'name' => 'Алексеев Пётр Иванович'],
            ['email' => 'student2@ppseducrm.local', 'name' => 'Борисова Анна Сергеевна'],
            ['email' => 'student3@ppseducrm.local', 'name' => 'Волков Никита Андреевич'],
            ['email' => 'student4@ppseducrm.local', 'name' => 'Громова Ольга Дмитриевна'],
            ['email' => 'student5@ppseducrm.local', 'name' => 'Данилов Артём Павлович'],
        ];

        $students = collect();
        foreach ($studentNames as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('password')]
            );
            $user->assignRole('student');
            $students->push(Student::firstOrCreate(
                ['user_id' => $user->id],
                ['study_group_id' => $group->id, 'course' => 1]
            ));
        }

        $classrooms = collect([
            ['number' => '101', 'building' => 'Корпус А', 'capacity' => 30],
            ['number' => '205', 'building' => 'Корпус А', 'capacity' => 25],
            ['number' => '310', 'building' => 'Корпус Б', 'capacity' => 20],
        ])->map(fn (array $data) => Classroom::firstOrCreate(
            ['building' => $data['building'], 'number' => $data['number']],
            ['capacity' => $data['capacity'], 'equipment' => ['projector' => true]]
        ));

        $statuses = Applicant::STATUSES;
        $applicantData = [
            ['Иванов', 'Сергей', 'Алексеевич', '112-233-445 95'],
            ['Попова', 'Анастасия', 'Игоревна', '123-456-789 00'],
            ['Сидоров', 'Максим', 'Олегович', '234-567-890 12'],
            ['Фёдорова', 'Екатерина', 'Николаевна', '345-678-901 23'],
            ['Морозов', 'Артём', 'Викторович', '456-789-012 34'],
            ['Новикова', 'Дарья', 'Павловна', '567-890-123 45'],
            ['Козлов', 'Илья', 'Романович', '678-901-234 56'],
            ['Лебедева', 'Виктория', 'Андреевна', '789-012-345 67'],
            ['Соколов', 'Кирилл', 'Дмитриевич', '890-123-456 78'],
            ['Орлова', 'Полина', 'Сергеевна', '901-234-567 89'],
        ];

        foreach ($applicantData as $index => [$last, $first, $middle, $snils]) {
            $applicant = Applicant::firstOrCreate(
                ['snils' => $snils],
                [
                    'last_name' => $last,
                    'first_name' => $first,
                    'middle_name' => $middle,
                    'email' => Str::slug("{$last}-{$first}").'@example.ru',
                    'phone' => '+7 (9'.str_pad((string) $index, 2, '0', STR_PAD_LEFT).') 000-00-00',
                    'status' => $statuses[$index % count($statuses)],
                    'manager_id' => $manager->id,
                ]
            );

            if ($index === 3) {
                Contract::firstOrCreate(
                    ['number' => 'ДОГ-2024-001'],
                    [
                        'applicant_id' => $applicant->id,
                        'status' => Contract::STATUS_SIGNED,
                        'signed_at' => now()->subDays(2),
                        'signed_by_manager_id' => $manager->id,
                    ]
                );
            }
        }

        $subjects = ['Математика', 'Физика', 'Информатика', 'Экономика', 'Английский язык'];
        $types = ScheduleSlot::TYPES;
        $weekStart = Carbon::now()->startOfWeek();

        for ($i = 0; $i < 20; $i++) {
            $dayOffset = intdiv($i, 4);
            $hour = 9 + ($i % 4) * 2;
            $startsAt = $weekStart->copy()->addDays($dayOffset)->setTime($hour, 0);
            $endsAt = $startsAt->copy()->addHours(1)->addMinutes(30);

            ScheduleSlot::create([
                'subject' => $subjects[$i % count($subjects)],
                'type' => $types[$i % count($types)],
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'teacher_id' => $teachers[$i % $teachers->count()]->id,
                'classroom_id' => $classrooms[$i % $classrooms->count()]->id,
                'study_group_id' => $group->id,
            ]);
        }
    }
}
