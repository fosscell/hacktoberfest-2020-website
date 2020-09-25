const timer = document.querySelector(".timer");
const daysEl = timer.querySelector(".days .value");
const hoursEl = timer.querySelector(".hours .value");
const minutesEl = timer.querySelector(".minutes .value");
const secondsEl = timer.querySelector(".seconds .value");

const eventTime = "October 3, 2020 9:00:00 AM GMT+05:30";
let countDownInterval;

const startCountDown = (timeStr) => {
  const timeEvt = Date.parse(timeStr);
  const timeCur = new Date();
  const timeVal =
    timeEvt.valueOf() -
    timeCur.valueOf() +
    timeCur.getTimezoneOffset() * 60 * 1000;

  console.log(timeVal);

  if (timeVal < 0) {
    setTime(0, 0, 0, 0);
    clearInterval(countDownInterval);
    return;
  }

  const time = new Date(timeVal);

  setTime(
    time.getDate() - 1,
    time.getHours(),
    time.getMinutes(),
    time.getSeconds()
  );
};

const setTime = (days, hours, minutes, seconds) => {
  daysEl.textContent = withZero(days);
  hoursEl.textContent = withZero(hours);
  minutesEl.textContent = withZero(minutes);
  secondsEl.textContent = withZero(seconds);
};

const withZero = (num) => (num > 9 ? num : `0${num}`);

startCountDown(eventTime);
countDownInterval = setInterval(() => startCountDown(eventTime), 1000);
