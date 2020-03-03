/*************************************************************************************************/
/* ****************************************** DONNEES ****************************************** */
/*************************************************************************************************/

var previewButton = document.getElementById('slider-preview');
var nextButton = document.getElementById('slider-next');


var photo1 = document.getElementById('photo1').value; 
var photo2 = document.getElementById('photo2').value; 
var photo3 = document.getElementById('photo3').value; 
var photo4 = document.getElementById('photo4').value; 

var images = ["admin/uploads/annonces/"+photo1,
                "admin/uploads/annonces/"+photo2,
                "admin/uploads/annonces/"+photo3,
                "admin/uploads/annonces/"+photo4];

var modifImg = document.querySelector('img');

var cursor = 0;
/*************************************************************************************************/
/* ***************************************** FONCTIONS ***************************************** */
/*************************************************************************************************/

function nextPict()
{
    cursor++;
    if(cursor>3)
    {
        cursor=0;
    }
    modifImg.setAttribute('src',images[cursor]);
    console.log(images[cursor]);
    
}

function prevPict()
{
    cursor--;
    if(cursor<0)
    {
        cursor=3;
    }
    modifImg.setAttribute('src',images[cursor]);
    console.log(images[cursor]);
    
 
}
/*************************************************************************************************/
/* ************************************** CODE PRINCIPAL *************************************** */
/*************************************************************************************************/

nextButton.addEventListener('click',nextPict)
previewButton.addEventListener('click',prevPict)