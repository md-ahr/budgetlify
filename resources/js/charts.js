import Chart from 'chart.js/auto';

/** @type {Chart[]} */
const chartInstances = [];

function isDark() {
    return document.documentElement.classList.contains('dark');
}

function themeColors() {
    if (isDark()) {
        return {
            text: '#94a3b8',
            textStrong: '#f1f5f9',
            grid: 'rgba(148, 163, 184, 0.12)',
            tooltipBg: 'rgba(15, 23, 42, 0.96)',
            tooltipBorder: 'rgba(148, 163, 184, 0.22)',
            trendLine: '#cbd5e1',
        };
    }

    return {
        text: '#64748b',
        textStrong: '#0f172a',
        grid: 'rgba(100, 116, 139, 0.18)',
        tooltipBg: '#ffffff',
        tooltipBorder: '#e2e8f0',
        trendLine: '#64748b',
    };
}

function baseOptions() {
    const t = themeColors();

    return {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            legend: {
                labels: {
                    color: t.text,
                    boxWidth: 10,
                    boxHeight: 10,
                    padding: 16,
                    usePointStyle: true,
                },
            },
            tooltip: {
                backgroundColor: t.tooltipBg,
                titleColor: t.textStrong,
                bodyColor: t.text,
                borderColor: t.tooltipBorder,
                borderWidth: 1,
                padding: 12,
                cornerRadius: 10,
                displayColors: true,
            },
        },
    };
}

function parseJsonDataset(el, key) {
    try {
        return JSON.parse(el.dataset[key] || '[]');
    } catch {
        return [];
    }
}

/**
 * @typedef {{ labels: string[], values: number[], hint?: string }} ExpenseRangeBundle
 * @typedef {Record<string, ExpenseRangeBundle>} ExpenseRanges
 */

/**
 * @param {HTMLCanvasElement} canvas
 * @returns {ExpenseRanges|null}
 */
function parseExpenseRanges(canvas) {
    try {
        const raw = canvas.dataset.expenseRanges;
        if (!raw) {
            return null;
        }

        const parsed = JSON.parse(raw);

        return parsed && typeof parsed === 'object' ? parsed : null;
    } catch {
        return null;
    }
}

/**
 * @param {HTMLCanvasElement} canvas
 * @param {ExpenseRanges} ranges
 * @param {string} key
 */
function applyExpenseRangeToChart(canvas, ranges, key) {
    const bundle = ranges[key] ?? ranges['7d'];
    if (!bundle || !Array.isArray(bundle.labels) || !Array.isArray(bundle.values)) {
        return;
    }

    const chart = Chart.getChart(canvas);
    if (!chart) {
        return;
    }

    chart.data.labels = [...bundle.labels];
    chart.data.datasets[0].data = [...bundle.values];

    const maxRotation = key === '365d' ? 45 : 0;
    const xScale = chart.options.scales?.x;
    if (xScale && typeof xScale === 'object' && 'ticks' in xScale && xScale.ticks && typeof xScale.ticks === 'object') {
        xScale.ticks.maxRotation = maxRotation;
    }

    chart.update();

    const hint = document.getElementById('dashboard-expense-hint');
    if (hint && typeof bundle.hint === 'string') {
        hint.textContent = bundle.hint;
    }
}

let dashboardExpenseRangeBound = false;

function bindDashboardExpenseRangeListener() {
    if (dashboardExpenseRangeBound) {
        return;
    }

    dashboardExpenseRangeBound = true;

    document.addEventListener('change', (event) => {
        const target = event.target;
        if (!(target instanceof HTMLSelectElement) || target.id !== 'dashboard-expense-range') {
            return;
        }

        const canvas = document.querySelector('[data-budgetlify-chart="dashboard-expenses"]');
        if (!(canvas instanceof HTMLCanvasElement)) {
            return;
        }

        const ranges = parseExpenseRanges(canvas);
        if (!ranges) {
            return;
        }

        applyExpenseRangeToChart(canvas, ranges, target.value);
    });
}

function currencyTick(value) {
    const n = Number(value);
    if (Number.isNaN(n)) {
        return '';
    }

    return '$' + n.toLocaleString(undefined, { maximumFractionDigits: 0 });
}

function destroyCharts() {
    chartInstances.forEach((c) => c.destroy());
    chartInstances.length = 0;
}

export function initBudgetlifyCharts() {
    destroyCharts();

    document.querySelectorAll('[data-budgetlify-chart]').forEach((node) => {
        if (!(node instanceof HTMLCanvasElement)) {
            return;
        }

        const canvas = node;
        const kind = canvas.dataset.budgetlifyChart;
        const labels = parseJsonDataset(canvas, 'labels');
        const t = themeColors();
        const base = baseOptions();

        if (kind === 'dashboard-expenses') {
            bindDashboardExpenseRangeListener();

            const datasetLabel = canvas.dataset.datasetLabel || '';
            const ranges = parseExpenseRanges(canvas);
            const select = document.getElementById('dashboard-expense-range');
            const selectedKey =
                select?.value && ranges?.[select.value] ? select.value : ranges ? '7d' : '';

            let expenseLabels = labels;
            let expenseValues = parseJsonDataset(canvas, 'values');

            if (ranges && selectedKey && ranges[selectedKey]) {
                expenseLabels = [...ranges[selectedKey].labels];
                expenseValues = [...ranges[selectedKey].values];
            }

            const xMaxRotation = selectedKey === '365d' ? 45 : 0;

            const chart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels: expenseLabels,
                    datasets: [
                        {
                            label: datasetLabel,
                            data: expenseValues,
                            backgroundColor: 'rgba(79, 70, 229, 0.88)',
                            hoverBackgroundColor: 'rgba(79, 70, 229, 1)',
                            borderWidth: 0,
                            borderRadius: 8,
                            borderSkipped: false,
                        },
                    ],
                },
                options: {
                    ...base,
                    plugins: {
                        ...base.plugins,
                        legend: { display: false },
                        tooltip: {
                            ...base.plugins.tooltip,
                            callbacks: {
                                label(ctx) {
                                    const v = ctx.parsed.y;

                                    return datasetLabel
                                        ? `${datasetLabel}: $${Number(v).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
                                        : `$${Number(v).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: t.text, maxRotation: xMaxRotation, autoSkip: true },
                            border: { display: false },
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: t.grid },
                            ticks: {
                                color: t.text,
                                callback: (v) => currencyTick(v),
                            },
                            border: { display: false },
                        },
                    },
                },
            });
            chartInstances.push(chart);

            if (ranges?.[selectedKey]?.hint) {
                const hint = document.getElementById('dashboard-expense-hint');
                if (hint) {
                    hint.textContent = ranges[selectedKey].hint;
                }
            }

            return;
        }

        if (kind === 'analytics-monthly') {
            const barValues = parseJsonDataset(canvas, 'barValues');
            const lineValues = parseJsonDataset(canvas, 'lineValues');
            const barLabel = canvas.dataset.barLabel || '';
            const lineLabel = canvas.dataset.lineLabel || '';

            const chart = new Chart(canvas, {
                data: {
                    labels,
                    datasets: [
                        {
                            type: 'bar',
                            label: barLabel,
                            data: barValues,
                            backgroundColor: 'rgba(79, 70, 229, 0.82)',
                            hoverBackgroundColor: 'rgba(79, 70, 229, 0.95)',
                            borderRadius: 8,
                            borderSkipped: false,
                            order: 2,
                        },
                        {
                            type: 'line',
                            label: lineLabel,
                            data: lineValues,
                            borderColor: t.trendLine,
                            backgroundColor: 'transparent',
                            tension: 0.35,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: t.trendLine,
                            pointBorderColor: isDark() ? '#0f172a' : '#ffffff',
                            pointBorderWidth: 2,
                            borderWidth: 2,
                            order: 1,
                        },
                    ],
                },
                options: {
                    ...base,
                    plugins: {
                        ...base.plugins,
                        tooltip: {
                            ...base.plugins.tooltip,
                            callbacks: {
                                label(ctx) {
                                    const v = ctx.parsed.y;

                                    return `${ctx.dataset.label}: $${Number(v).toLocaleString(undefined, { maximumFractionDigits: 0 })}`;
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: t.text, maxRotation: 0 },
                            border: { display: false },
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: t.grid },
                            ticks: {
                                color: t.text,
                                callback: (v) => currencyTick(v),
                            },
                            border: { display: false },
                        },
                    },
                },
            });
            chartInstances.push(chart);

            return;
        }

        if (kind === 'analytics-category') {
            const values = parseJsonDataset(canvas, 'values');
            const colors = parseJsonDataset(canvas, 'colors');
            const fallback = [
                'rgba(79, 70, 229, 0.9)',
                'rgba(34, 197, 94, 0.9)',
                'rgba(129, 140, 248, 0.9)',
                'rgba(251, 191, 36, 0.9)',
                'rgba(148, 163, 184, 0.85)',
            ];
            const bg = colors.length ? colors : fallback.slice(0, values.length);

            const chart = new Chart(canvas, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [
                        {
                            data: values,
                            backgroundColor: bg,
                            borderWidth: isDark() ? 2 : 3,
                            borderColor: isDark() ? '#0f172a' : '#ffffff',
                            hoverOffset: 6,
                        },
                    ],
                },
                options: {
                    ...base,
                    cutout: '62%',
                    plugins: {
                        ...base.plugins,
                        legend: { display: false },
                        tooltip: {
                            ...base.plugins.tooltip,
                            callbacks: {
                                label(ctx) {
                                    const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                                    const pct = total ? Math.round((ctx.parsed / total) * 100) : 0;

                                    return `${ctx.label}: ${pct}%`;
                                },
                            },
                        },
                    },
                },
            });
            chartInstances.push(chart);

            return;
        }

        if (kind === 'analytics-savings') {
            const values = parseJsonDataset(canvas, 'values');
            const datasetLabel = canvas.dataset.datasetLabel || '';

            const chart = new Chart(canvas, {
                type: 'line',
                data: {
                    labels,
                    datasets: [
                        {
                            label: datasetLabel,
                            data: values,
                            borderColor: '#22c55e',
                            backgroundColor: isDark() ? 'rgba(34, 197, 94, 0.18)' : 'rgba(34, 197, 94, 0.12)',
                            fill: true,
                            tension: 0.35,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#22c55e',
                            pointBorderColor: isDark() ? '#0f172a' : '#ffffff',
                            pointBorderWidth: 2,
                            borderWidth: 2,
                        },
                    ],
                },
                options: {
                    ...base,
                    plugins: {
                        ...base.plugins,
                        legend: { display: false },
                        tooltip: {
                            ...base.plugins.tooltip,
                            callbacks: {
                                label(ctx) {
                                    const v = ctx.parsed.y;

                                    return `${datasetLabel}: $${Number(v).toLocaleString(undefined, { maximumFractionDigits: 0 })}`;
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: t.text, maxRotation: 0 },
                            border: { display: false },
                        },
                        y: {
                            beginAtZero: false,
                            grid: { color: t.grid },
                            ticks: {
                                color: t.text,
                                callback: (v) => currencyTick(v),
                            },
                            border: { display: false },
                        },
                    },
                },
            });
            chartInstances.push(chart);

            return;
        }

        if (kind === 'analytics-flow') {
            const values = parseJsonDataset(canvas, 'values');
            const incomeLabel = canvas.dataset.incomeLabel || '';
            const expenseLabel = canvas.dataset.expenseLabel || '';

            const chart = new Chart(canvas, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        {
                            label: canvas.dataset.datasetLabel || '',
                            data: values,
                            backgroundColor: ['rgba(34, 197, 94, 0.88)', 'rgba(239, 68, 68, 0.82)'],
                            hoverBackgroundColor: ['rgba(34, 197, 94, 1)', 'rgba(239, 68, 68, 0.95)'],
                            borderRadius: 8,
                            borderSkipped: false,
                        },
                    ],
                },
                options: {
                    indexAxis: 'y',
                    ...base,
                    plugins: {
                        ...base.plugins,
                        legend: { display: false },
                        tooltip: {
                            ...base.plugins.tooltip,
                            callbacks: {
                                label(ctx) {
                                    const v = ctx.parsed.x;
                                    const raw = ctx.label === labels[0] ? incomeLabel : expenseLabel;
                                    const prefix = raw ? `${raw}: ` : '';

                                    return `${prefix}$${Number(v).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                                },
                            },
                        },
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            grid: { color: t.grid },
                            ticks: {
                                color: t.text,
                                callback: (v) => currencyTick(v),
                            },
                            border: { display: false },
                        },
                        y: {
                            grid: { display: false },
                            ticks: { color: t.textStrong, font: { weight: '500' } },
                            border: { display: false },
                        },
                    },
                },
            });
            chartInstances.push(chart);
        }
    });
}

let themeDebounce;

export function watchChartTheme() {
    const observer = new MutationObserver(() => {
        clearTimeout(themeDebounce);
        themeDebounce = setTimeout(() => {
            initBudgetlifyCharts();
        }, 60);
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class'],
    });
}
