// var trafficchart = document.getElementById("trafficflow");
// var saleschart = document.getElementById("sales");
//
// // new
//
//
// // new
// var myChart2 = new Chart(saleschart, {
//     type: 'line',
//     data: {
//         labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
//         datasets: [{
//             label: 'Borrowed',
//             data: ['0', '0', '0', '0', '1', '1', '1', '2', '2', '2', '3', '3'], // Your data for dataset 1
//             backgroundColor: "rgba(48, 164, 255, 0.2)",
//             borderColor: "rgba(48, 164, 255, 0.8)",
//             fill: true,
//             borderWidth: 1
//         },
//         {
//             label: 'Returned',
//             data: ['1', '1', '1', '1', '2', '2', '2', '3', '3', '3', '4', '4'],
//             backgroundColor: "rgba(255, 99, 132, 0.2)",
//             borderColor: "rgba(255, 99, 132, 0.8)",
//             fill: true,
//             borderWidth: 1
//         }]
//     },
//     options: {
//         animation: {
//             duration: 2000,
//             easing: 'easeOutQuart',
//         },
//         scales: {
//             y: {
//                 beginAtZero: true,
//                 ticks: {
//                     stepSize: 1, // Set the step size to 1
//                     callback: function(value, index, values) {
//                         return value.toFixed(0); // Format to remove decimals
//                     }
//                 }
//             }
//         },
//         plugins: {
//             legend: {
//                 display: true,
//                 position: 'bottom'
//
//             },
//             title: {
//                 display: true,
//                 text: 'Circulations Count',
//                 position: 'left',
//             },
//         },
//     }
// });
