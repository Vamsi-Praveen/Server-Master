<?php
header('Content-Type: application/json');

function getCpuUsage() {
    $load = sys_getloadavg();
    // Using the 1-minute load average as the CPU usage
    return $load[0];
}
function getMemoryUsage() {
    $free = shell_exec('free');
    $free = (string)trim($free);
    $free_arr = explode("\n", $free);
    $mem = explode(" ", $free_arr[1]);
    $mem = array_filter($mem);
    $mem = array_merge($mem);
    $memory_usage = $mem[2] / $mem[1] * 100;

    return $memory_usage;
}

echo json_encode(['cpu_usage' => getCpuUsage(),'memory_usage'=>getMemoryUsage()]);
?>