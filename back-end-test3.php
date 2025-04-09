<?php

// There are 20 classes in the school.
// 5 class have 35 students,
// 6 classes have 45 students,
// 10 classes have 30 students
// and 4 classes have 40 students.
// The average age of all students is 20 years and 8 months.
// Write a program count the total student of each class who the age larger or smaller than the average age 6 month.


// Ở đây đề bài đang có vấn đề tổng số lớp là 25 nhưng đề bài chỉ nói rằng có 20 lớp.

// Từ đề bài => Các lớp học được chia thành 4 nhóm
$classes = [
    ['count' => 5, 'students' => 35],
    ['count' => 6, 'students' => 45],
    ['count' => 10, 'students' => 30],
    ['count' => 4, 'students' => 40],
];

//Tuổi trung bình: 20 năm 8 tháng = 248 tháng.
$averageAgeMonths = 20 * 12 + 8;

//Chênh lệch cần kiểm tra: ±6 tháng (tức là >254 tháng hoặc <242 tháng).
//Vì không có dữ liệu thực, ta sinh tuổi học sinh ngẫu nhiên xung quanh giá trị trung bình
// (phạm vi ±24 tháng để đảm bảo tính đa dạng).
$deviation = 24;

// khởi tạo array thống kê
$statistics = [];
foreach ($classes as $class) {
    $statistics[$class['students']] = [
        'total_classes' => $class['count'],
        'total_students' => $class['students'] * $class['count'],
        'older' => 0,
        'younger' => 0
    ];
}

// sinh tuổi ngẫu nhiên, và phân loại học sinh older | younger
foreach ($classes as $class) {
    $studentPerClass = $class['students'];
    $numClasses = $class['count'];

    for ($i = 0; $i < $numClasses; $i++) {
        $olderCount = 0;
        $youngerCount = 0;


        for ($s = 0; $s < $studentPerClass; $s++) {
            // Sinh tuổi ngẫu nhiên
            $age = mt_rand($averageAgeMonths - $deviation, $averageAgeMonths + $deviation);

            // Phân loại học sinh older | younger
            if ($age > $averageAgeMonths + 6) {
                $olderCount++;
            } elseif ($age < $averageAgeMonths - 6) {
                $youngerCount++;
            }
        }

        // Cập nhật thống kê
        $statistics[$studentPerClass]['older'] += $olderCount;
        $statistics[$studentPerClass]['younger'] += $youngerCount;
    }
}

foreach ($statistics as $size => $data) {
    $total = $data['older'] + $data['younger'];
    $percentage = ($total / $data['total_students']) * 100;

    echo "Lớp $size học sinh:\n";
    echo "- Tổng lớp: {$data['total_classes']}\n";
    echo "- Tổng học sinh: {$data['total_students']}\n";
    echo "- Học sinh lớn hơn trung bình: {$data['older']}\n";
    echo "- Học sinh nhỏ hơn trung bình: {$data['younger']}\n";
    echo "- Tỷ lệ: " . number_format($percentage, 2) . "%\n";
    echo "----------------------------------\n";
}

?>