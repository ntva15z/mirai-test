<?php

/* Write a program to find the 20% of people with the largest age difference from other people in a large city. */

/* Tìm 20% người có độ chênh lệch tuổi lớn nhất so với những người khác trong thành phố.
 * Độ chênh lệch được tính dựa trên tổng chênh lệch tuổi giữa một người và tất cả người còn lại.
 */

/* Sử dụng prefix sum để tính tổng chênh lệch
 * 1. Sắp xếp lại mảng tuổi, tránh việc tạo ra các số chênh lệch âm
 * 2. Tính prefix sum
 * 3. Tính chênh lêch
 * Giả sử mảng tuổi đã được sắp xếp tăng dần: ages[0] ≤ ages[1] ≤ ... ≤ ages[N-1].
 * Chênh lệch với người trước:
        Tổng chênh lệch giữa ages[i] và tất cả người đứng trước nó (từ ages[0] đến ages[i-1]).
        Do mảng đã sắp xếp, mọi phần tử trước i đều ≤ ages[i], nên chênh lệch là ages[i] - ages[j] với j < i.
        Tổng này được tính nhanh bằng công thức ages[i] * i - prefixSum[i-1].

 * Chênh lệch với người sau:
        Tổng chênh lệch giữa ages[i] và tất cả người đứng sau nó (từ ages[i+1] đến ages[N-1]).
        Do mảng đã sắp xếp, mọi phần tử sau i đều ≥ ages[i], nên chênh lệch là ages[j] - ages[i] với j > i.
        Tổng này được tính nhanh bằng công thức (totalSum - prefixSum[i]) - ages[i] * (N - i - 1).
 * Từ đó Tổng chênh lệch tuổi của 1 người ở vị trí i = Chênh lệch với người trước + Chênh lệch với người sau
    diff[i] = (ages[i] * i − prefixSum[i−1]) + (totalSum - prefixSum[i]) - ages[i] * (N - i - 1);
 */


function findTop20PercentWithLargestAgeDifference($ages)
{
    // sắp xếp lại mảng
    sort($ages);
    $n = count($ages);
    if ($n == 0) return [];

    // Tính prefix sum
    $prefixSum = [];
    $prefixSum[0] = $ages[0];
    for ($i = 1; $i < $n; $i++) {
        $prefixSum[$i] = $prefixSum[$i - 1] + $ages[$i];
    }
    $totalSum = $prefixSum[$n - 1];

    // Tính tổng chênh lệch cho từng người
    $people = [];
    for ($i = 0; $i < $n; $i++) {
        $currentAge = $ages[$i];
        $sumBefore = ($i > 0) ? $prefixSum[$i - 1] : 0;
        $sumAfter = $totalSum - $prefixSum[$i];
        $diff = ($currentAge * $i - $sumBefore) + ($sumAfter - $currentAge * ($n - $i - 1));
        $people[] = ['age' => $currentAge, 'diff' => $diff];
    }

    // Sắp xếp lại danh sách theo chênh lệch
    usort($people, function ($a, $b) {
        return $b['diff'] - $a['diff'];
    });

    // lấy top 20%
    $topCount = max(1, (int)round(0.2 * $n));
    return array_slice($people, 0, $topCount);
}

// Ví dụ Input
$ages = [];
for ($i = 0; $i < 100; $i++) {
    // 70% tuổi trong khoảng 20-70
    if (mt_rand(1, 10) <= 70) {
        $ages[] = mt_rand(20, 70);
    }
    // 30% tuổi trong khoảng (1-19 hoặc 71-100)
    else {
        $ages[] = mt_rand(1, 10) <= 5 ? mt_rand(1, 19) : mt_rand(71, 100);
    }
}
$result = findTop20PercentWithLargestAgeDifference($ages);

// Output
echo "Danh sách tuổi: [" . implode(", ", $ages) . "]\n";
echo "Top 20% người có độ chênh lệch tuổi lớn nhất sẽ là những người có độ tuổi sau:\n";

// nhóm kết quả lại theo tuôi chênh lệch
$ageGroups = [];
foreach ($result as $person) {
    $age = $person['age'];
    if (!isset($ageGroups[$age])) {
        $ageGroups[$age] = [
            'count' => 1,
            'total_diff' => $person['diff']
        ];
    } else {
        $ageGroups[$age]['count']++;
        $ageGroups[$age]['total_diff'] += $person['diff'];
    }
}


// Hiển thị kết quả
foreach ($ageGroups as $age => $data) {
    $averageDiff = $data['total_diff'] / $data['count'];
    echo "Tuổi: $age, Số người: {$data['count']}, \n";
}
