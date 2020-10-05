// group1 - Jan, Feb, Mar
// group2 - Apr, May, Jun,
// group3 - Jul, Aug, Sep,
// group1 - Oct, Nov, Dec,

// group2 - Jan, Feb, Mar,
// group3 - Apr, May, Jun,
// group1 - Jul, Aug, Sep,
// group2 - Oct, Nov, Dec,

// group3 - Jan, Feb, Mar
// group1 - Apr, May, Jun,
// group2 - Jul, Aug, Sep,
// group3 - Oct, Nov, Dec,

function showGroupByDate(startDate, currentDate) {
  if (!(startDate instanceof Date) || !(currentDate instanceof Date)) {
    throw new Error('startDate and currentDate should be a Date objects');
  }

  const monthDiff = (d1, d2) =>
    Math.abs((d2.getFullYear() - d1.getFullYear()) * 12 - d1.getMonth() + d2.getMonth());

  const n = monthDiff(startDate, currentDate); // months since start
  
  if (n % 9 === 0 || (n-1) % 9 === 0 || (n-2) % 9 === 0) {
    return 'Group 1';
  }

  if ((n-3) % 9 === 0 || (n-4) % 9 === 0 || (n-5) % 9 === 0) {
    return 'Group 2'; 
  }

  if ((n-6) % 9 === 0 || (n-7) % 9 === 0 || (n-8) % 9 === 0) {
    return 'Group 3'
  }
}

module.exports = showGroupByDate;