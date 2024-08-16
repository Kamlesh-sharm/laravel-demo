'use strict';

const chartOne = document.getElementById('roomBookingChart').getContext('2d');
const myBookingChart = new Chart(chartOne, {
  type: 'line',
  data: {
    labels: monthArr,
    datasets: [{
      label: 'Monthly Booking',
      data: bookingArr,
      borderColor: '#1d7af3',
      pointBorderColor: '#FFF',
      pointBackgroundColor: '#1d7af3',
      pointBorderWidth: 2,
      pointHoverRadius: 4,
      pointHoverBorderWidth: 1,
      pointRadius: 4,
      backgroundColor: 'transparent',
      fill: true,
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    legend: {
      position: 'bottom',
      labels: {
        padding: 10,
        fontColor: '#1d7af3'
      }
    },
    tooltips: {
      bodySpacing: 4,
      mode: 'nearest',
      intersect: 0,
      position: 'nearest',
      xPadding: 10,
      yPadding: 10,
      caretPadding: 10
    },
    layout: {
      padding: {
        left: 15,
        right: 15,
        top: 15,
        bottom: 15
      }
    },
    scales: {
      yAxes: [{
        ticks: {
          stepSize: 1
        }
      }]
    }
  }
});

const chartTwo = document.getElementById('roomIncomeChart').getContext('2d');
const myIncomeChart = new Chart(chartTwo, {
  type: 'line',
  data: {
    labels: monthArr,
    datasets: [{
      label: 'Monthly Income',
      data: incomeArr,
      borderColor: '#1d7af3',
      pointBorderColor: '#FFF',
      pointBackgroundColor: '#1d7af3',
      pointBorderWidth: 2,
      pointHoverRadius: 4,
      pointHoverBorderWidth: 1,
      pointRadius: 4,
      backgroundColor: 'transparent',
      fill: true,
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    legend: {
      position: 'bottom',
      labels: {
        padding: 10,
        fontColor: '#1d7af3'
      }
    },
    tooltips: {
      bodySpacing: 4,
      mode: 'nearest',
      intersect: 0,
      position: 'nearest',
      xPadding: 10,
      yPadding: 10,
      caretPadding: 10
    },
    layout: {
      padding: {
        left: 15,
        right: 15,
        top: 15,
        bottom: 15
      }
    }
  }
});


const chartThree = document.getElementById('packageBookingChart').getContext('2d');
const myPackageBookingChart = new Chart(chartThree, {
  type: 'line',
  data: {
    labels: monthArr,
    datasets: [{
      label: 'Monthly Booking',
      data: pBookingArr,
      borderColor: '#1d7af3',
      pointBorderColor: '#FFF',
      pointBackgroundColor: '#1d7af3',
      pointBorderWidth: 2,
      pointHoverRadius: 4,
      pointHoverBorderWidth: 1,
      pointRadius: 4,
      backgroundColor: 'transparent',
      fill: true,
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    legend: {
      position: 'bottom',
      labels: {
        padding: 10,
        fontColor: '#1d7af3'
      }
    },
    tooltips: {
      bodySpacing: 4,
      mode: 'nearest',
      intersect: 0,
      position: 'nearest',
      xPadding: 10,
      yPadding: 10,
      caretPadding: 10
    },
    layout: {
      padding: {
        left: 15,
        right: 15,
        top: 15,
        bottom: 15
      }
    },
    scales: {
      yAxes: [{
        ticks: {
          stepSize: 1
        }
      }]
    }
  }
});

const chartFour = document.getElementById('packageIncomeChart').getContext('2d');
const myPackageIncomeChart = new Chart(chartFour, {
  type: 'line',
  data: {
    labels: monthArr,
    datasets: [{
      label: 'Monthly Income',
      data: pIncomeArr,
      borderColor: '#1d7af3',
      pointBorderColor: '#FFF',
      pointBackgroundColor: '#1d7af3',
      pointBorderWidth: 2,
      pointHoverRadius: 4,
      pointHoverBorderWidth: 1,
      pointRadius: 4,
      backgroundColor: 'transparent',
      fill: true,
      borderWidth: 2
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    legend: {
      position: 'bottom',
      labels: {
        padding: 10,
        fontColor: '#1d7af3'
      }
    },
    tooltips: {
      bodySpacing: 4,
      mode: 'nearest',
      intersect: 0,
      position: 'nearest',
      xPadding: 10,
      yPadding: 10,
      caretPadding: 10
    },
    layout: {
      padding: {
        left: 15,
        right: 15,
        top: 15,
        bottom: 15
      }
    }
  }
});
