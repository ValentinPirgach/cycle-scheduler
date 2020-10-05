const showGroupByDate = require('./app');

describe('Start of year cases', () => {
  const startDate = '2021.01.01';

  test.each`
    date            | expected
    ${'2021.01.01'} | ${'Group 1'}
    ${'2021.02.01'} | ${'Group 1'}
    ${'2021.03.01'} | ${'Group 1'}
    ${'2021.04.01'} | ${'Group 2'}
    ${'2021.05.01'} | ${'Group 2'}
    ${'2021.06.01'} | ${'Group 2'}
    ${'2021.07.01'} | ${'Group 3'}
    ${'2021.08.01'} | ${'Group 3'}
    ${'2021.09.01'} | ${'Group 3'}
    ${'2021.10.01'} | ${'Group 1'}
    ${'2021.11.01'} | ${'Group 1'}
    ${'2021.12.01'} | ${'Group 1'}
    ${'2022.01.01'} | ${'Group 2'}
    ${'2022.02.01'} | ${'Group 2'}
    ${'2022.03.01'} | ${'Group 2'}
    ${'2022.04.01'} | ${'Group 3'}
    ${'2022.05.01'} | ${'Group 3'}
    ${'2022.06.01'} | ${'Group 3'}
    ${'2022.07.01'} | ${'Group 1'}
    ${'2022.08.01'} | ${'Group 1'}
    ${'2022.09.01'} | ${'Group 1'}
    ${'2022.10.01'} | ${'Group 2'}
    ${'2022.11.01'} | ${'Group 2'}
    ${'2022.12.01'} | ${'Group 2'}
    ${'2023.01.01'} | ${'Group 3'}
    ${'2023.02.01'} | ${'Group 3'}
    ${'2023.03.01'} | ${'Group 3'}
    ${'2023.04.01'} | ${'Group 1'}
    ${'2023.05.01'} | ${'Group 1'}
    ${'2023.06.01'} | ${'Group 1'}
    ${'2023.07.01'} | ${'Group 2'}
    ${'2023.08.01'} | ${'Group 2'}
    ${'2023.09.01'} | ${'Group 2'}
    ${'2023.10.01'} | ${'Group 3'}
    ${'2023.11.01'} | ${'Group 3'}
    ${'2023.12.01'} | ${'Group 3'}
    ${'2024.01.01'} | ${'Group 1'}
    ${'2024.02.01'} | ${'Group 1'}
    ${'2024.03.01'} | ${'Group 1'}
  `(`For date $date should return $expected`, ({date, expected}) => {
    expect(showGroupByDate(new Date(startDate), new Date(date))).toBe(expected);
  })
});

describe('Starts in November', () => {
  const startDate = '2020.11.01';

  test.each`
    date            | expected
    ${'2020.11.01'} |	${'Group 1'}
    ${'2020.11.01'} |	${'Group 1'}
    ${'2020.12.01'} |	${'Group 1'}
    ${'2021.01.01'} |	${'Group 1'}
    ${'2021.02.01'} |	${'Group 2'}
    ${'2021.03.01'} |	${'Group 2'}
    ${'2021.04.01'} |	${'Group 2'}
    ${'2021.05.01'} |	${'Group 3'}
    ${'2021.06.01'} |	${'Group 3'}
    ${'2021.07.01'} |	${'Group 3'}
    ${'2021.08.01'} |	${'Group 1'}
    ${'2021.09.01'} |	${'Group 1'}
    ${'2021.10.01'} |	${'Group 1'}
    ${'2021.11.01'} |	${'Group 2'}
    ${'2021.12.01'} |	${'Group 2'}
    ${'2022.01.01'} |	${'Group 2'}
    ${'2022.02.01'} |	${'Group 3'}
    ${'2022.03.01'} |	${'Group 3'}
    ${'2022.04.01'} |	${'Group 3'}
    ${'2022.05.01'} |	${'Group 1'}
    ${'2022.06.01'} |	${'Group 1'}
    ${'2022.07.01'} |	${'Group 1'}
    ${'2022.08.01'} |	${'Group 2'}
    ${'2022.09.01'} |	${'Group 2'}
    ${'2022.10.01'} |	${'Group 2'}
    ${'2022.11.01'} |	${'Group 3'}
    ${'2022.12.01'} |	${'Group 3'}
    ${'2023.01.01'} |	${'Group 3'}
    ${'2023.02.01'} |	${'Group 1'}
    ${'2023.03.01'} |	${'Group 1'}
    ${'2023.04.01'} |	${'Group 1'}
    ${'2023.05.01'} |	${'Group 2'}
    ${'2023.06.01'} |	${'Group 2'}
    ${'2023.07.01'} |	${'Group 2'}
    ${'2023.08.01'} |	${'Group 3'}
    ${'2023.09.01'} |	${'Group 3'}
    ${'2023.10.01'} |	${'Group 3'}
    ${'2023.11.01'} |	${'Group 1'}
    ${'2023.12.01'} |	${'Group 1'}
    ${'2024.01.01'} |	${'Group 1'}
  `(`For date $date should return $expected`, ({date, expected}) => {
    expect(showGroupByDate(new Date(startDate), new Date(date))).toBe(expected);
  })
});