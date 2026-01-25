import "../css/dashboard.scss";
import Chart from "chart.js/auto";
import { get } from "./ajax";

window.addEventListener("DOMContentLoaded", function () {
document.getElementById("selected-year").addEventListener("change", function() {
    localStorage.setItem("selectValue", this.value);
    location.reload();
});
  const ctx = document.getElementById("yearToDateChart");
  let yearSelect = document.getElementById("selected-year");
  let year = yearSelect.value;
  const savedValue = localStorage.getItem("selectValue");
    if (savedValue) {
        year = savedValue;
        yearSelect.value = savedValue;
    } 
  get("/stats/ytd?year="+year)
    .then((response) => response.json())
    .then((response) => {
      let expensesData = Array(12).fill(null);
      let expensesDetailData = Array(12).fill(null);
      let incomeDetailData = Array(12).fill(null);
      let incomeData = Array(12).fill(null);

      response.forEach(({ month, month_total, expense_total, all_expenses, all_incomes, income_total }) => {
        expensesData[month - 1] = expense_total;
        expensesDetailData[month - 1] = all_expenses.map(obj => {
            const [key, value] = Object.entries(obj)[0];
            return `${key}: ${Math.abs(parseFloat(value))}`;
        });
        incomeDetailData[month - 1] = all_incomes.map(obj => {
            const [key, value] = Object.entries(obj)[0];
            return `${key}: ${Math.abs(parseFloat(value))}`;
        });

        incomeData[month - 1] = income_total;
      });

      new Chart(ctx, {
        type: "bar",
        data: {
          labels: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
          ],
          datasets: [
            {
              label: "Expense",
              data: expensesData,
              extraData: expensesDetailData,
              borderWidth: 1,
              backgroundColor: "rgba(255, 99, 132, 0.2)",
              borderColor: "rgba(255, 99, 132, 1)",
            },
            {
              label: "Income",
              data: incomeData,
              extraData: incomeDetailData,
              borderWidth: 1,
              backgroundColor: "rgba(75, 192, 192, 0.2)",
              borderColor: "rgba(75, 192, 192, 1)",
            },
          ],
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
            },
          },
          plugins: {
            tooltip: {
              callbacks: {
                label: function (context) {
                  const value = context.raw;
                  const label = context.dataset.label
                  const extra = context.dataset.extraData[context.dataIndex];
                  return [
                    `${label}: ${value}`,
                      ...extra
                  ];
                }
              }
            }
          }
        },
      });
    });
});
