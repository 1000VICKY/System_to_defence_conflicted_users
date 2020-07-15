    <script>
      $(function (e) {
        $("#previous").on("click", function (e) {
          history.back();
        });
        $("#next").on("click", function (e) {
          history.go(1);
        });
      })
      flatpickr("#event_start", {
        dateFormat: "Y-m-d"
      });
    </script>
  </body>
</html>
