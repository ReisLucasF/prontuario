<!-- gráfico de linhas -->
<script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable(<?php echo json_encode($data); ?>);

        var options = {
          title: 'Economia anual',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>

    <!-- gráfico de pizza -->
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var dataArray = [['Nome', 'Gastos']];
        <?php
          foreach ($data2 as $item) {
              echo "dataArray.push(['{$item[0]}', {$item[1]}]);";
          }
        ?>

        var chartData = google.visualization.arrayToDataTable(dataArray);

        var options = {
            title: 'Porcentagem dos Gastos',
            is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(chartData, options);
      }
    </script>

      <!-- gráfico de comparação -->
  <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawVisualization);

      function drawVisualization() {
          var data = google.visualization.arrayToDataTable(<?php echo json_encode($data); ?>);
        
          var options = {
              title: 'Comparação Mensal de Despesas e Receitas',
              vAxis: {title: 'Valor'},
              hAxis: {title: 'Mês'},
              seriesType: 'bars',
              series: {2: {type: 'line'}}
          };

          var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
          chart.draw(data, options);
      }
  </script>