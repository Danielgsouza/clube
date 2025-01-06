let payments;
let inputOption = document.getElementById("inputOption");
let outputOption = document.getElementById("outputOption");
let navTabs = document.querySelector('.nav-tabs');
document.addEventListener("DOMContentLoaded", async function() {
  // console.log("OK")
  payments = await getSQL("./queries/sql_get_payments.php");

  if (inputOption.classList.contains("active")) {
    inputOption.click();
  } 
  
  if(outputOption.classList.contains("active")){
    outputOption.click();
  }
});

inputOption.addEventListener("click", async function() {
  let htmlBody = "";
  navTabs.classList.remove('nav-danger');
  navTabs.classList.add('nav-success');
  payments.data.filter((payment) => payment.tipo == "E").map((item) => {
    htmlBody += `<tr>`;
    htmlBody += `<td>${item.id}</td>`;
    htmlBody += `<td>${item.nome_pagamento}</td>`;
    htmlBody += `<td>${item.mes}</td>`;
    htmlBody += `<td>${item.ano}</td>`;
    htmlBody += `<td>${item.valor}</td>`;
    htmlBody += `<td>${item.formatted_date}</td>`;
    htmlBody += `<td>`;
    htmlBody += `<ul>`;
    htmlBody += `<a href="./new-payments.php?id=${item.id}&nome=${item.nome_pagamento}&mes=${item.mes}&ano=${item.ano}&valor=${item.valor}&tipo=${item.tipo}" class="mx-1 edit-link" ><i class="fas fa-edit"></i></a>`;
    htmlBody += `</ul>`;
    htmlBody += `</td>`;
    htmlBody += `</tr>`;
  });

  $("#tbodyInput").html(htmlBody);
  $("#table-input").DataTable();

});

outputOption.addEventListener("click", async function() {
  navTabs.classList.remove('nav-success');
  navTabs.classList.add('nav-danger');

  let htmlBody = "";
  payments.data.filter((payment) => payment.tipo == "S").map((item) => {
    htmlBody += `<tr>`;
    htmlBody += `<td>${item.id}</td>`;
    htmlBody += `<td>${item.nome_pagamento}</td>`;
    // htmlBody += `<td>${item.cpf}</td>`;
    htmlBody += `<td>${item.mes}</td>`;
    htmlBody += `<td>${item.ano}</td>`;
    htmlBody += `<td>${item.valor}</td>`;
    htmlBody += `<td>${item.formatted_date}</td>`;
    htmlBody += `<td>`;
    htmlBody += `<ul>`;
    htmlBody += `<a href="./new-payments.php?id=${item.id}&nome=${item.nome_pagamento}&mes=${item.mes}&ano=${item.ano}&valor=${item.valor}&tipo=${item.tipo}" class="mx-1 edit-link" ><i class="fas fa-edit"></i></a>`;
    htmlBody += `</ul>`;
    htmlBody += `</td>`;
    htmlBody += `</tr>`;
  });

  $("#tbodyOutput").html(htmlBody);
  $("#table-output").DataTable();

});

