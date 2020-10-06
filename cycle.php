<?php
class CycleSchedule {
  private $config;

  function __construct($groups_count, $group_duration) {
    preg_match_all("/(\d+)([mhMY])/", $group_duration, $duration);

    $this->config = (object)[
      'groups_count' => (int)$groups_count,
      'duration' => (int)$duration[1][0],
      'scale' => $duration[2][0],
    ]; 
  }

  private function build_groups() {
    $groups = array();

    for ($g = 0; $g < $this->config->groups_count; $g++) {
      $condition = array();

      for ($d = 0; $d < $this->config->duration; $d++) {
        $k = (int)$this->config->duration * $this->config->groups_count;
        $a = $d + $g * $this->config->duration;

        array_push($condition, "((\$n - {$a}) % {$k} === 0)");
      }
      
      array_push($groups, (object)[
        'id' => "Group ".($g + 1),
        'condition' => implode(" || ", $condition),
      ]);
    }

    return $groups;
  }

  private function get_diff($date_start, $date_end) {
    $interval = $date_start->diff($date_end);

    if ($this->config->scale == 'M') {
      return (int)($interval->format('%y') * 12) + $interval->format('%m');
    }

    if ($this->config->scale == 'h') {
      return (int)$interval->format('%h');
    }
  }

  public function show_group_by_date($start_date, $current_date) {
    $groups = $this->build_groups();

    $n = $this->get_diff($current_date, $start_date);

    foreach ($groups as $key => $group) {

      if (eval("return $group->condition;")) {
        return $group;
      }
    }
  }
}

$schedule_hours = new CycleSchedule(3, '1h');

$test_hours = array(
  '2020-10-06T16:00:00-04:00' => 'Group 1',
  '2020-10-06T16:30:00-04:00' => 'Group 1',
  '2020-10-06T17:00:00-04:00' => 'Group 2',
  '2020-10-06T17:30:00-04:00' => 'Group 2',
  '2020-10-06T18:00:00-04:00' => 'Group 3',
  '2020-10-06T18:30:00-04:00' => 'Group 3',
  '2020-10-06T19:00:00-04:00' => 'Group 1',
  '2020-10-06T19:30:00-04:00' => 'Group 1',
);

foreach ($test_hours as $hour => $group) {
  $r = $schedule_hours->show_group_by_date(new DateTime('2020-10-06T16:00:00-04:00'), new DateTime($hour));

  $result = $r->id === $group ? "OK" : "FAIL";

  echo "\n$result | Value: $r->id | Expected: $group\n";
}

echo "\n\n";

$schedule_months = new CycleSchedule(3, '3M');

$test_dates = array(
  '2021-01-01' => 'Group 1',
  '2021-02-01' => 'Group 1',
  '2021-03-01' => 'Group 1',
  '2021-04-01' => 'Group 2',
  '2021-05-01' => 'Group 2',
  '2021-06-01' => 'Group 2',
  '2021-07-01' => 'Group 3',
  '2021-08-01' => 'Group 3',
  '2021-09-01' => 'Group 3',
  '2021-10-01' => 'Group 1',
  '2021-11-01' => 'Group 1',
  '2021-12-01' => 'Group 1',
  '2022-01-01' => 'Group 2',
  '2022-02-01' => 'Group 2',
  '2022-03-01' => 'Group 2',
  '2022-04-01' => 'Group 3',
  '2022-05-01' => 'Group 3',
  '2022-06-01' => 'Group 3',
  '2022-07-01' => 'Group 1',
  '2022-08-01' => 'Group 1',
  '2022-09-01' => 'Group 1',
  '2022-10-01' => 'Group 2',
  '2022-11-01' => 'Group 2',
  '2022-12-01' => 'Group 2',
  '2023-01-01' => 'Group 3',
  '2023-02-01' => 'Group 3',
  '2023-03-01' => 'Group 3',
  '2023-04-01' => 'Group 1',
  '2023-05-01' => 'Group 1',
  '2023-06-01' => 'Group 1',
  '2023-07-01' => 'Group 2',
  '2023-08-01' => 'Group 2',
  '2023-09-01' => 'Group 2',
  '2023-10-01' => 'Group 3',
  '2023-11-01' => 'Group 3',
  '2023-12-01' => 'Group 3',
  '2024-01-01' => 'Group 1',
  '2024-02-01' => 'Group 1',
  '2024-03-01' => 'Group 1',
);


foreach ($test_dates as $date => $group) {
  $r = $schedule_months->show_group_by_date(new DateTime('2021-01-01'), new DateTime($date));

  $result = $r->id === $group ? "OK" : "FAIL";

  echo "\n$result | Value: $r->id | Expected: $group\n";
}