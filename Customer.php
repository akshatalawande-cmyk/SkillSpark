<?php
$phone = "+91 98765 43210";
$email = "support@skillspark.com";
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SkillSpark Support</title>

<style>

/* DARK SEA-BLUE BACKGROUND */

body{
margin:0;
font-family:Segoe UI, Arial;
background:#05386B;
display:flex;
flex-direction:column;
align-items:center;
justify-content:center;
height:100vh;
color:#fff;
overflow:hidden;
}

/* LOGO CONTAINER */

.logo-box{
text-align:center;
margin-bottom:30px;
perspective:800px;
}

/* BOOK */

.book{
width:100px;
height:60px;
position:relative;
margin:auto;
transform-style:preserve-3d;
}

/* WHITE PAGES */

.page{
width:50px;
height:60px;
position:absolute;
top:0;
background:#fff;
border-radius:2px;
box-shadow:0 4px 10px rgba(0,0,0,0.2);
}

.left{
left:0;
transform-origin:right;
animation:openLeft 2s forwards;
}

.right{
right:0;
transform-origin:left;
animation:openRight 2s forwards;
}

@keyframes openLeft{
0%{transform:rotateY(0);}
100%{transform:rotateY(-150deg);}
}

@keyframes openRight{
0%{transform:rotateY(0);}
100%{transform:rotateY(150deg);}
}

/* SPARK PARTICLES */

.spark{
position:absolute;
width:6px;
height:6px;
background:gold;
border-radius:50%;
opacity:0;
animation:spark 2s infinite;
}

.spark:nth-child(1){left:45px;top:-5px;animation-delay:.1s;}
.spark:nth-child(2){left:55px;top:-5px;animation-delay:.4s;}
.spark:nth-child(3){left:35px;top:-5px;animation-delay:.7s;}
.spark:nth-child(4){left:65px;top:-5px;animation-delay:1s;}

@keyframes spark{
0%{transform:translateY(0) scale(.5);opacity:1;}
100%{transform:translateY(-60px) scale(1.5);opacity:0;}
}

/* LOGO TEXT */

.logo-text{
margin-top:20px;
font-size:36px;
font-weight:bold;
background:linear-gradient(90deg,#00d4ff,#ffd700,#00d4ff);
background-size:200%;
-webkit-background-clip:text;
color:transparent;
animation:shine 3s linear infinite;
}

@keyframes shine{
0%{background-position:0%;}
100%{background-position:200%;}
}

/* CARD */

.card{
background:white;
padding:30px;
border-radius:12px;
width:420px;
text-align:center;
box-shadow:0 10px 25px rgba(0,0,0,.25);
animation:fadeIn 2s ease;
color:#05386B;
}

@keyframes fadeIn{
0%{opacity:0;transform:translateY(20px);}
100%{opacity:1;transform:translateY(0);}
}

/* CONTACT INFO */

.contact div{
margin:10px 0;
cursor:pointer;
font-weight:bold;
color:#4b6bfb;
transition:.3s;
}

.contact div:hover{
color:#2c4de0;
}

/* POPUP */

.popup{
display:none;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,.6);
justify-content:center;
align-items:center;
}

.popup-box{
background:white;
padding:25px;
border-radius:10px;
text-align:center;
width:300px;
color:#05386B;
}

.popup-box button{
margin-top:15px;
padding:8px 14px;
border:none;
background:#4b6bfb;
color:white;
border-radius:6px;
cursor:pointer;
}

</style>
</head>

<body>

<!-- LOGO -->

<div class="logo-box">

<div class="book">
<div class="page left"></div>
<div class="page right"></div>

<div class="spark"></div>
<div class="spark"></div>
<div class="spark"></div>
<div class="spark"></div>
</div>

<div class="logo-text">SkillSpark</div>

</div>

<!-- CARD -->

<div class="card">

<h2>Contact Our Support Team</h2>

<p>
Welcome to the <b>SkillSpark Community</b>.  
Our mission is to help learners grow their skills and succeed in their careers.
If you need help, our support team is always ready to assist you.
</p>

<div class="contact">

<div onclick="showPopup('phone')">
📞 Phone: <?php echo $phone; ?>
</div>

<div onclick="showPopup('email')">
📧 Email: <?php echo $email; ?>
</div>

</div>

</div>

<!-- POPUP -->

<div class="popup" id="popup">

<div class="popup-box">

<h3 id="title"></h3>
<p id="text"></p>

<button onclick="closePopup()">Close</button>

</div>

</div>

<script>

window.onload=function(){
const sparks=document.querySelectorAll(".spark");

sparks.forEach((s,i)=>{
s.style.animationDelay=(i*0.3)+"s";
});
};

/* POPUP */

function showPopup(type){

let title="";
let text="";

if(type==="phone"){
title="Call Support";
text="You can contact us at: <?php echo $phone; ?>";
}

if(type==="email"){
title="Email Support";
text="Send us an email at: <?php echo $email; ?>";
}

document.getElementById("title").innerText=title;
document.getElementById("text").innerText=text;

document.getElementById("popup").style.display="flex";
}

function closePopup(){
document.getElementById("popup").style.display="none";
}

</script>

</body>
</html>