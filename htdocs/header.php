<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {
  margin: 0;
  font-family: Arial, Helvetica, sans-serif;
}

.topnav {
  overflow: hidden;
  background-color: #333;
}

.topnav a {
  float: left;
  display: block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 17px;
}

.topnav a:hover {
  background-color: #ddd;
  color: black;
}

.topnav a.active {
  background-color: #04AA6D;
  color: white;
}

.topnav .icon {
  display: none;
}

@media screen and (max-width: 600px) {
  .topnav a:not(:first-child) {display: none;}
  .topnav a.icon {
    float: right;
    display: block;
  }
}

@media screen and (max-width: 600px) {
  .topnav.responsive {position: relative;}
  .topnav.responsive .icon {
    position: absolute;
    right: 0;
    top: 0;
  }
  .topnav.responsive a {
    float: none;
    display: block;
    text-align: left;
  }
}
input[type=text], input[type=number],input[type=date], select{
    font-size: 17px;
    box-sizing: border-box;
    width: 100%;
    height: 30px;
    padding-left: 10px;
    padding-right: 10px;
    color: #333333;
    text-align: left;
    border: 1px solid #d6d6d6;
    border-radius: 4px;
    background: white;
    outline: none;
    box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
}
input[type=checkbox]{
    width: 20px;
    height: 20px;
}
textarea{
    font-size: 17px;
    box-sizing: border-box;
    width: 100%;
    height: 60px;
    padding-left: 10px;
    padding-right: 10px;
    color: #333333;
    text-align: left;
    border: 1px solid #d6d6d6;
    border-radius: 4px;
    background: white;
    outline: none;
    box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
}
input[type=text]:focus, input[type=number]:focus ,input[type=date]:focus, select:focus{
    box-shadow: 0 0 5pt 2pt #D3D3D3;
}
input[type=submit]{
    box-sizing: border-box;
    padding-left: 10px;
    padding-right: 10px;   
    font-weight: bold;

}
input[type=submit]:hover{
    box-shadow: 0 0 5pt 0.5pt #D3D3D3;
}
input[type=text]:hover, input[type=number]:hover ,input[type=date]:hover, select:hover, input[type=radio]:hover{
    box-shadow: 0 0 5pt 0.5pt #D3D3D3;
}

</style>


