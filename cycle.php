<?php

class CycleGroup {
  public $index;
  public $next_cycle_offset;
  private $duration;
  private $cycle_offset;
  private $rules = array();

  function __construct($group_index, $group_duration, $cycle_offset) {
    $this->index = $group_index;
    $this->duration = $group_duration;
    $this->cycle_offset = $cycle_offset;

    $this->build_rules();
  }

  private function build_rules() {
    for($durationIndex = 0; $durationIndex < $this->duration; $durationIndex++) {
      array_push($this->rules, function ($dateOffset) use ($durationIndex) {
        $groupOffset = $durationIndex + $this->index * $this->duration;
        return ($dateOffset - $groupOffset) % $this->cycle_offset === 0;
      });
    }
  }

  public function is_current($date_offset) {
    foreach($this->rules as $rule_index => $rule_func) {
      if ($rule_func($date_offset)) {
        $this->next_cycle_offset = $date_offset + $this->cycle_offset - $rule_index;
        return true;
      }
    }

    return false;
  }
}

class CycleSchedule {
  private $config;
  private $groups;
  private $groups_count;
  private $group_duration;
  private $scale;

  function __construct($groups_count, $group_duration) {
    preg_match_all("/(\d+)([mhMY])/", $group_duration, $duration);

    $this->groups_count = (int)$groups_count;
    $this->group_duration = (int)$duration[1][0];
    $this->scale = $duration[2][0];
    $this->cycle_offset = (int)$this->group_duration * $this->groups_count;

    $this->groups = $this->build_groups();
  }

  public function show_group_by_date($start_date, $current_date) {
    $date_offset = $this->get_diff($current_date, $start_date);

    foreach ($this->groups as $key => $group) {
      if ($group->is_current($date_offset)) {
        return $group;
      }
    }
  }

  private function build_groups() {
    $groups = array();

    for ($group_index = 0; $group_index < $this->groups_count; $group_index++) {
      array_push($groups, new CycleGroup($group_index, $this->group_duration, $this->cycle_offset));
    }

    return $groups;
  }

  public function get_next_cycle_date($start_date, $next_date_offset) {
    $next_date = clone $start_date;

    if ($this->scale == 'M') {
      return $next_date->modify("first day of +$next_date_offset months");
    }

    if ($this->scale == 'h') {
      return $next_date->modify("+$next_date_offset hours");
    }
  }

  private function get_diff($date_start, $date_end) {
    $interval = $date_start->diff($date_end);

    if ($this->scale == 'M') {
      return (int)($interval->format('%y') * 12) + $interval->format('%m');
    }

    if ($this->scale == 'h') {
      return (int)$interval->format('%h');
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

foreach ($test_hours as $hour => $expectedGroup) {
  $start_date = new DateTime('2020-10-06T16:00:00-04:00');
  $group = $schedule_hours->show_group_by_date($start_date, new DateTime($hour));
  $group_next_cycle_date = $schedule_hours->get_next_cycle_date($start_date, $group->next_cycle_offset)->format('c');

  $gid = "Group ".($group->index + 1);
  $result = $gid === $expectedGroup ? "<b style=\"background-color: green; color: white\">OK</b>" : "<b style=\"background-color: red; color: white\">FAIL</b>";

  echo "<p>$result | Value: $gid | Expected: $expectedGroup </p>";
  echo "<p> Date c: $hour <br/> Date e: $group_next_cycle_date </p>";
}

echo "<br/><hr/><br/>";

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


foreach ($test_dates as $date => $expectedGroup) {
  $start_date = new DateTime('2021-01-01');
  $group = $schedule_months->show_group_by_date($start_date, new DateTime($date));
  $group_next_cycle_date = $schedule_months->get_next_cycle_date($start_date, $group->next_cycle_offset)->format('Y-m-d');

  $gid = "Group ".($group->index + 1);
  $result = $gid === $expectedGroup ? "<b style=\"background-color: green; color: white\">OK</b>" : "<b style=\"background-color: red; color: white\">FAIL</b>";

  echo "<p>$result | Value: $gid | Expected: $expectedGroup</p>";
  echo "<p> Date c: $date <br/> Date e: $group_next_cycle_date </p>";
}