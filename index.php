<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body>
    <?php
     $fechaZ =date("Y-m-d H:i:s");  


    ?>
  <h1><?= $fechaZ  ?></h1>
  <br>

  <script>
async function hacerPeticion(){

        const config = {
            method: 'get',
            dataType: 'json',
            url: 'nestle.php'
        }
        const buena =  await axios(config);
        console.log(buena);
        const info = buena.data; 
      /*
      fetch('nestle.php', {  // aqui cambias el nombre de la pagina por la tuya
          method: 'GET',
          body: JSON.stringify({
            'solicitud': true
          }),
          headers: {
            'Content-Type': 'application/json'
          }
        })
        .then(function (response) {


          console.log(response.data);
        });
        */

    }
        var f = new Date();
        console.log(f);
        setInterval(hacerPeticion,600000);
  </script>
</body>
</html>



       
       
       
       
       
       
       
       
       
       
       
       
       
       
       

