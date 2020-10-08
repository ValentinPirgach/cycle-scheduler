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

  const groupsCount = 3;
  const duration = 3; // Months

  const groups = buildGroups(groupsCount, duration);
  const foundGroup = groups.find(group => group.isCurrent(n)) || {};

  return `Group ${foundGroup.groupIndex + 1}`;
}

class Group {
  constructor(groupIndex, groupDuration, cycleOffset) {
    this.groupDuration = groupDuration;
    this.groupIndex = groupIndex;
    this.cycleOffset = cycleOffset;

    this.matchArray = Array(this.groupDuration).fill().map((item, durationIndex) => {
      const groupOffset = durationIndex + this.groupIndex * this.groupDuration;
      console.log(`(dateOffset - ${groupOffset}) % ${this.cycleOffset} === 0`);
      return (dateOffset) => (dateOffset - groupOffset) % this.cycleOffset === 0;
    })
  }

  isCurrent(dateOffset) {
    return this.matchArray.some(matchFunc => matchFunc(dateOffset));
  }
}

function buildGroups (count, duration) {
  const cycleOffset = count * duration;

  return Array(count).fill().map((item, index) => new Group(index, duration, cycleOffset))
}

module.exports = showGroupByDate;