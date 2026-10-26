import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Shared Settings
    |--------------------------------------------------------------------------
    */

    const chartFont = {
        family: 'Instrument Sans, sans-serif'
    };

    const statusColors = [
        '#3498db', // Incoming
        '#ff5c7a', // Assigned
        '#ff9f43', // Ongoing
        '#feca57', // Completed
        '#48b9b9'  // Archived
    ];

    const priorityColors = [
        '#3498db', // Urgent
        '#ff5c7a', // High
        '#ff9f43', // Normal
        '#feca57'  // Low
    ];

    const chartColors = [
        '#3498db',
        '#48b9b9',
        '#7c6ee6',
        '#ff9f43',
        '#feca57',
        '#95a5a6',
        '#5dade2',
        '#58d68d',
        '#af7ac5',
        '#f5b041'
    ];


    /*
    |--------------------------------------------------------------------------
    | Chart Value Plugin
    |--------------------------------------------------------------------------
    */

    const chartValuePlugin = {

        id: 'chartValuePlugin',

        afterDatasetsDraw(chart) {

            const {
                ctx
            } = chart;

            ctx.save();

            chart.data.datasets.forEach((dataset, datasetIndex) => {

                const meta = chart.getDatasetMeta(datasetIndex);

                meta.data.forEach((element, index) => {

                    const value = dataset.data[index];

                    if (
                        value === null ||
                        value === undefined ||
                        value === 0
                    ) {
                        return;
                    }

                    ctx.font =
                        '600 12px "Instrument Sans", sans-serif';

                    ctx.fillStyle = '#495057';

                    ctx.textAlign = 'center';

                    ctx.textBaseline = 'middle';


                    /*
                    |--------------------------------------------------------------------------
                    | Doughnut
                    |--------------------------------------------------------------------------
                    */

                    if (chart.config.type === 'doughnut') {

                        const position =
                            element.tooltipPosition();

                        ctx.fillText(
                            value,
                            position.x,
                            position.y
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Bar
                    |--------------------------------------------------------------------------
                    */

                    else if (chart.config.type === 'bar') {

                        const position =
                            element.tooltipPosition();

                        if (chart.options.indexAxis === 'y') {

                            ctx.textAlign = 'left';

                            ctx.fillText(
                                value,
                                position.x + 8,
                                position.y
                            );

                        } else {

                            ctx.textAlign = 'center';

                            ctx.fillText(
                                value,
                                position.x,
                                position.y - 8
                            );

                        }

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Line
                    |--------------------------------------------------------------------------
                    */

                    else if (chart.config.type === 'line') {

                        const position =
                            element.tooltipPosition();

                        ctx.textAlign = 'center';

                        ctx.fillText(
                            value,
                            position.x,
                            position.y - 12
                        );

                    }

                });

            });

            ctx.restore();
        }
    };


    /*
    |--------------------------------------------------------------------------
    | Request Status Chart
    |--------------------------------------------------------------------------
    */

    const statusCanvas = document.getElementById(
        'requestStatusChart'
    );

    if (statusCanvas) {

        const statusData = JSON.parse(
            statusCanvas.dataset.chart
        );

        new Chart(statusCanvas, {

            type: 'doughnut',

            plugins: [
                chartValuePlugin
            ],

            data: {

                labels: Object.keys(statusData),

                datasets: [{

                    data: Object.values(statusData),

                    backgroundColor: statusColors,

                    borderWidth: 2,

                    borderColor: '#ffffff'
                }]
            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '62%',

                plugins: {

                    legend: {

                        position: 'bottom',

                        labels: {

                            usePointStyle: true,

                            pointStyle: 'circle',

                            padding: 18,

                            font: chartFont
                        }
                    },

                    tooltip: {

                        callbacks: {

                            label: function (context) {

                                return ` ${context.label}: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Monthly Request Volume
    |--------------------------------------------------------------------------
    */

    const monthlyCanvas = document.getElementById(
        'monthlyRequestChart'
    );

    if (monthlyCanvas) {

        const labels = JSON.parse(
            monthlyCanvas.dataset.labels
        );

        const counts = JSON.parse(
            monthlyCanvas.dataset.counts
        );

        new Chart(monthlyCanvas, {

            type: 'line',

            plugins: [
                chartValuePlugin
            ],

            data: {

                labels: labels,

                datasets: [{

                    label: 'Requests Received',

                    data: counts,

                    tension: 0.35,

                    fill: true,

                    borderWidth: 3,

                    pointRadius: 4,

                    pointHoverRadius: 6,

                    pointBorderWidth: 2,

                    pointBackgroundColor: '#ffffff'
                }]
            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                interaction: {

                    intersect: false,

                    mode: 'index'
                },

                scales: {

                    x: {

                        grid: {

                            display: false
                        },

                        ticks: {

                            font: chartFont
                        }
                    },

                    y: {

                        beginAtZero: true,

                        ticks: {

                            precision: 0,

                            font: chartFont
                        }
                    }
                },

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        callbacks: {

                            label: function (context) {

                                return ` Requests: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Priority Distribution
    |--------------------------------------------------------------------------
    */

    const priorityCanvas = document.getElementById(
        'priorityChart'
    );

    if (priorityCanvas) {

        const priorityData = JSON.parse(
            priorityCanvas.dataset.chart
        );

        new Chart(priorityCanvas, {

            type: 'doughnut',

            plugins: [
                chartValuePlugin
            ],

            data: {

                labels: Object.keys(priorityData),

                datasets: [{

                    data: Object.values(priorityData),

                    backgroundColor: priorityColors,

                    borderWidth: 2,

                    borderColor: '#ffffff'
                }]
            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                cutout: '62%',

                plugins: {

                    legend: {

                        position: 'bottom',

                        labels: {

                            usePointStyle: true,

                            pointStyle: 'circle',

                            padding: 18,

                            font: chartFont
                        }
                    },

                    tooltip: {

                        callbacks: {

                            label: function (context) {

                                return ` ${context.label}: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Request Type Distribution
    |--------------------------------------------------------------------------
    */

    const requestTypeCanvas = document.getElementById(
        'requestTypeChart'
    );

    if (requestTypeCanvas) {

        const requestTypeData = JSON.parse(
            requestTypeCanvas.dataset.chart
        );

        new Chart(requestTypeCanvas, {

            type: 'bar',

            plugins: [
                chartValuePlugin
            ],

            data: {

                labels: Object.keys(requestTypeData),

                datasets: [{

                    label: 'Requests',

                    data: Object.values(requestTypeData),

                    backgroundColor: chartColors,

                    borderRadius: 6,

                    borderSkipped: false
                }]
            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                scales: {

                    x: {

                        grid: {

                            display: false
                        },

                        ticks: {

                            font: chartFont
                        }
                    },

                    y: {

                        beginAtZero: true,

                        ticks: {

                            precision: 0,

                            font: chartFont
                        }
                    }
                },

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        callbacks: {

                            label: function (context) {

                                return ` Requests: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Services Requested
    |--------------------------------------------------------------------------
    */

    const servicesCanvas = document.getElementById(
        'servicesChart'
    );

    if (servicesCanvas) {

        const servicesData = JSON.parse(
            servicesCanvas.dataset.chart
        );

        const serviceLabels = Object.keys(
            servicesData
        );

        const serviceValues = Object.values(
            servicesData
        );

        new Chart(servicesCanvas, {

            type: 'bar',

            plugins: [
                chartValuePlugin
            ],

            data: {

                labels: serviceLabels,

                datasets: [{

                    label: 'Requests',

                    data: serviceValues,

                    backgroundColor: chartColors.slice(
                        0,
                        serviceLabels.length
                    ),

                    borderRadius: 6,

                    borderSkipped: false
                }]
            },

            options: {

                indexAxis: 'y',

                responsive: true,

                maintainAspectRatio: false,

                scales: {

                    x: {

                        beginAtZero: true,

                        ticks: {

                            precision: 0,

                            font: chartFont
                        }
                    },

                    y: {

                        grid: {

                            display: false
                        },

                        ticks: {

                            font: chartFont
                        }
                    }
                },

                plugins: {

                    legend: {
                        display: false
                    },

                    tooltip: {

                        callbacks: {

                            label: function (context) {

                                return ` Requests: ${context.raw}`;
                            }
                        }
                    }
                }
            }
        });
    }

    /*
|--------------------------------------------------------------------------
| Organizations Requested
|--------------------------------------------------------------------------
*/

const organizationsCanvas = document.getElementById(
    'organizationsChart'
);

if (organizationsCanvas) {

    const organizationsData = JSON.parse(
        organizationsCanvas.dataset.chart
    );

    const organizationLabels = Object.keys(
        organizationsData
    );

    const organizationValues = Object.values(
        organizationsData
    );


    new Chart(organizationsCanvas, {

        type: 'bar',

        plugins: [
            chartValuePlugin
        ],

        data: {

            labels: organizationLabels,

            datasets: [{

                label: 'Requests',

                data: organizationValues,

                backgroundColor: chartColors.slice(
                    0,
                    organizationLabels.length
                ),

                borderRadius: 6,

                borderSkipped: false
            }]
        },

        options: {

            indexAxis: 'y',

            responsive: true,

            maintainAspectRatio: false,

            scales: {

                x: {

                    beginAtZero: true,

                    ticks: {

                        precision: 0,

                        font: chartFont
                    }
                },

                y: {

                    grid: {

                        display: false
                    },

                    ticks: {

                        font: chartFont
                    }
                }
            },

            plugins: {

                legend: {

                    display: false
                },

                tooltip: {

                    callbacks: {

                        label: function (context) {

                            return ` Requests: ${context.raw}`;
                        }
                    }
                }
            }
        }
    });
}
});