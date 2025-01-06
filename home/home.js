let revenueChart = null; // Variável para armazenar o gráfico de receitas
let associateChart = null; // Variável para armazenar o gráfico de sócios
document.addEventListener("DOMContentLoaded", function() {
  
  // Fetch dos dados para o gráfico de sócios
  const currentYear = new Date().getFullYear();
  const yearSelect = document.getElementById("year-select");
  const yearPaymentsAssociate = document.getElementById("year");
  
  if(yearPaymentsAssociate.value)  filterDataByYearAssociate(yearPaymentsAssociate.value);

  yearPaymentsAssociate.addEventListener("change", function() {
    const select = yearPaymentsAssociate.value;
    filterDataByYearAssociate(select);
  });

  if(yearSelect.value)  filterDataByYear(yearSelect.value);

  yearSelect.addEventListener("change", function() {
    const selectedYear = yearSelect.value;
    filterDataByYear(selectedYear);
  });

  fetch("./queries/sql_get_associates.php")
    .then(response => response.json())
    .then(data => {
      // Verifica se a resposta é válida e contém os dados necessários
      if (data.length > 0) {

        // Extraindo os dados
        const label = [data.map(item => item.label)]; // "Ativo" ou "Inativo"
        const value = [data.map(item => Number(item.value))]; // Contagem de cada status
        const colors = ['#dc3545', '#1b9dff'];

        var options = {
          series: value[0],
          chart: {
          width: 380,
          type: 'pie',
          events: {
            dataPointSelection: function(event, chartContext, config) {
             window.location.href = "../users/associate.php";
            }
          }
        },
        labels: label[0],
        dataLabels: {
          enabled: true,
          formatter: function (val, opts) {
            return opts.w.globals.series[opts.seriesIndex]
          },
        },
        
        legend: {
          position: 'bottom', // Colocando a legenda na parte inferior
          horizontalAlign: 'center', // Centralizando a legenda
          offsetY: 10, // Ajuste da posição vertical da legenda, se necessário
          colors: colors === "1b9dff" ? "Ativo" : "Inativo", // Cores dos itens da legenda
        },
        colors: colors, 
        };

        var chart = new ApexCharts(document.querySelector("#associateChart"), options);
        chart.render();
        
      } else {
        console.error('Erro ao carregar os dados');
      }
    })
    .catch(error => {
      console.error('Erro na requisição:', error);
    });
    
  // Fetch dos dados para o gráfico de dependentes
  fetch("./queries/sql_get_dependents.php")
  .then(response => response.json())
  .then(data => {
    // Verifica se a resposta é válida e contém os dados necessários
    if (data.length > 0) {

      // Extraindo os dados
      const label = [data.map(item => item.label)]; // "Ativo" ou "Inativo"
      const value = [data.map(item => Number(item.value))]; // Contagem de cada status
      const colors = ['#1b9dff', '#dc3545'];

    
      var options = {
        series: value[0],
        chart: {
        width: 380,
        type: 'pie',
        events: {
          dataPointSelection: function(event, chartContext, config) {
            window.location.href = "../dependents/dependents.php";
          }
        }
      },
      labels: label[0],
      dataLabels: {
        enabled: true,
        formatter: function (val, opts) {
          return opts.w.globals.series[opts.seriesIndex]
        },
      },
      
      legend: {
        position: 'bottom', // Colocando a legenda na parte inferior
        horizontalAlign: 'center', // Centralizando a legenda
        offsetY: 10, // Ajuste da posição vertical da legenda, se necessário
        colors: colors === "1b9dff" ? "Ativo" : "Inativo", // Cores dos itens da legenda
      },
      colors: colors, 
      };

      var chart = new ApexCharts(document.querySelector("#dependentsChart"), options);
      chart.render();
      
    } else {
      console.error('Erro ao carregar os dados');
    }
  })
  .catch(error => {
    console.error('Erro na requisição:', error);
  });

  let revenueEntry = '';
  let revenueOutflow = '';

  function filterDataByYear(year) {
    
    // Limpa qualquer mensagem de erro anterior
    const noDataMessage = document.getElementById('no-data-message');
    if (noDataMessage) {
      noDataMessage.style.display = 'none';  // Esconde a mensagem de "não existe dados"
    }
  
    // Verifica se o gráfico já existe, e destrói antes de recriar
    if (revenueChart) {
      revenueChart.destroy(); // Destrói o gráfico existente
    }
  
    // Requisição para obter os dados de receita
    fetch("./queries/sql_get_revenues.php", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ year: year })
    })
    .then(response => response.json())
    .then(data => {

      // Configuração do gráfico ApexCharts para área
      const currentMonth = new Date().getMonth() + 1; // Janeiro é 0, então adicionamos 1

      // Filtrar os dados para obter os valores de entrada do mês atual
      const currentMonthData = data.filter(item => parseInt(item.MES.split('-')[1]) === currentMonth);
      revenueEntry = currentMonthData.map(item => item.ENTRADA);
      revenueOutflow = currentMonthData.map(item => item.SAIDA);

      // Atualizar o valor de entrada no card
      document.getElementById('entry').innerText = revenueEntry.length > 0 ? `R$ ${revenueEntry[0]}` : 'R$ 0';
      document.getElementById('outflow').innerText = revenueOutflow.length > 0 ? `R$ ${revenueOutflow[0]}` : 'R$ 0';

      const colors = ['#3eb95f', '#e74b2b'];
  
      if (data.length > 0) {
        // Dados encontrados, renderiza o gráfico
        var options = {
          series: [{
            name: 'Entrada',
            data: data.map(item => item.ENTRADA)
          }, {
            name: 'Saída',
            data: data.map(item => item.SAIDA)
          }],
          chart: {
            type: 'bar',
            height: 350,
            id: 'revenue-chart'
          },
          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: '55%',
              borderRadius: 5
            },
          },
          dataLabels: {
            enabled: false
          },
          stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
          },
          xaxis: {
            categories: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],
          },
          yaxis: {
            title: {
              text: 'R$ Faturamento'
            }
          },
          fill: {
            opacity: 1
          },
          tooltip: {
            y: {
              formatter: function (val) {
                return "R$ " + val;
              }
            }
          },
          legend: {
            position: 'bottom',
            horizontalAlign: 'center',
            offsetY: 10,
          },
          colors: colors,
        };
  
        // Cria o gráfico de receitas para o ano selecionado
        revenueChart = new ApexCharts(document.querySelector("#revenue-chart"), options);
        revenueChart.render();
      } else {
        // Se não houver dados, exibe a mensagem de "não existem dados"
        // showNoDataMessage();
      }
    })
    .catch(error => {
      console.error('Erro na requisição:', error);
      // showNoDataMessage();
    });
  }
  
  // Função para mostrar a mensagem de "Não existem dados para esse período"
  function showNoDataMessage() {
    const noDataMessage = document.getElementById('no-data-message');
    if (noDataMessage) {
      noDataMessage.style.display = 'block';  // Exibe a mensagem de "não existem dados"
    } else {
      const messageElement = document.createElement('div');
      messageElement.id = 'no-data-message';
      messageElement.style.textAlign = 'center';
      messageElement.style.marginBottom = '20px';
      messageElement.style.color = '#000';
      messageElement.style.fontSize = '18px';
      messageElement.style.fontWeight = 'bold';
      messageElement.innerText = 'Não existem dados para esse período';
      document.querySelector('#revenue-chart').parentElement.appendChild(messageElement);
    }
  }

  function filterDataByYearAssociate(year) {

    const noDataMessage = document.getElementById('no-data');
    if (noDataMessage) {
      noDataMessage.style.display = 'none';  // Esconde a mensagem de "não existe dados"
    }

    // Verifica se o gráfico já existe, e destrói antes de recriar
    if (associateChart) {
      associateChart.destroy(); // Destrói o gráfico existente
    }
    
    fetch("./queries/sql_get_payments.php", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ year: year })
    })
    .then(response => response.json())
    .then(data => {
      if (data.length > 0) {
  
        var options = {
          series: [{
            name: "Faturamento",
            data: data.map(item => item.value) // Aqui é onde você coloca os valores da série
          }],
          chart: {
            type: "area",
            height: 288
          },
          dataLabels: {
            enabled: true
          },
          legend: {
            position: "bottom",
          },
          xaxis: {
            categories: ["Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez"],  // Definindo os meses no eixo X
            title: {
              position: "bottom",
              text: "Meses"
            }
          },
          yaxis: {
            title: {
              text: "Valor"
            }
          }
          
        };
  
        associateChart = new ApexCharts(document.querySelector("#growth-chart"), options);
        associateChart.render();
      } else {
        showNoDataMessageAssociate();
        console.error('Nenhum dado encontrado.');
      }
    })
    .catch(error => {
      console.error('Erro na requisição:', error);
    });
  }

  function showNoDataMessageAssociate() {
    const noDataMessage = document.getElementById('no-data');
    if (noDataMessage) {
      noDataMessage.style.display = 'block';
    } else {
      const messageElement = document.createElement('div');
      messageElement.id = 'no-data';
      messageElement.style.textAlign = 'center';
      messageElement.style.marginBottom = '20px';
      messageElement.style.color = '#000';
      messageElement.style.fontSize = '14px';
      messageElement.style.fontWeight = 'bold';
      messageElement.innerText = 'Não existem dados para esse período!';
      document.querySelector('#growth-chart').parentElement.appendChild(messageElement);
    }
  }
    
});
