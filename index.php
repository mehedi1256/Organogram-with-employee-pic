<!DOCTYPE html>
<html>

<head>
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HRMS Organogram</title>
  <link rel="stylesheet" href="printlib/print.min.css">
  <script src="printlib/print.min.js"></script>
  <script type="text/javascript" src="jquery.js"></script>

  <link rel="stylesheet" href="jquery.orgchart.css" />

  <script src="jquery.orgchart.js"></script>
  <script type="text/javascript">
    $(function() {
      var members;
      $.ajax({
        url: "load.php",
        async: false,
        success: function(data) {
          console.log(data)
          members = $.parseJSON(data);

        },
      });

      //memberId,parentId,nameInfo
      for (var i = 0; i < members.length; i++) {
        var member = members[i];

        if (i == 0) {
          $("#mainContainer").append(
            "<li id=" + member.memberId + ">" + '<img src="https://hrms.waltonbd.com/' + member.image + '" width="57px" height="40px">' + member.nameInfo + "<br>" + member.deginfo + "</li>"
          );
        } else {
          if ($("#pr_" + member.parentId).length <= 0) {
            $("#" + member.parentId).append(
              "<ul id='pr_" +
              member.parentId +
              "'><li id=" +
              member.memberId +
              ">" +
              '<img src="https://hrms.waltonbd.com/' + member.image + '" width="57px" height="40px">' + member.nameInfo + "<br>" + member.deginfo +

              "</li></ul>"
            );
          } else {
            $("#pr_" + member.parentId).append(
              "<li id=" + member.memberId + ">" + '<img src="https://hrms.waltonbd.com/' + member.image + '" width="57px" height="40px">' + member.nameInfo + "<br>" + member.deginfo + "</li>"
            );
          }
        }
      }

      $("#mainContainer").orgChart({
        container: $("#main"),
        interactive: true,
        fade: true,
        speed: "slow",
      });
    });
  </script>
</head>
<!-- <img src='<?php //echo "https://hrms.waltonbd.com/".$data->PIC_URL_[0]; 
                ?>' id='img_id' width='200px' height='200px'/> -->

<body>
  <div id="none">
    <ul id="mainContainer" class="clearfix"></ul>
  </div>
  <div id="main"></div>
  <button id="btnNone" type="button" onclick="printb()">Print</button>
  <script>
    function printb() {
      document.getElementById('btnNone').style.display = 'none';
      window.print();
    }
  </script>
</body>

</html>