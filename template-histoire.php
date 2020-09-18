<?php
/*
 * Template Name: Page Histoire FR BACKUP
 */

get_header();
?>


<style>
/* NOTRE HISTOIRE */
    #notreHistoire section {
        max-width: 82.75862em;
        margin:auto;
        display:flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: stretch;
        align-content: stretch;
    }   
    #notreHistoire .titleUnderline {
        width:100%;
        position: relative;
    }   
    #notreHistoire .titleUnderline:after {
        content:'';
        display: block;
        height:5px;
        width:150px;
        background-color:grey;
        position: absolute;
        bottom:-2px;
        left:0;
    }    

    #notreHistoire section article{
        flex-basis: 100%;
        padding:3rem;
        display: flex;
        flex-direction: column;
    } 
    @media screen and (min-width:768px) {
        #notreHistoire section article{
            flex-basis: 50%;
        }    
        
    }
    @media screen and (min-width:1200px) {
        #notreHistoire section article{
            flex-basis: 33%;
        }    
        
    }
    
    #notreHistoire section .articlePicture {
        width: 100%;
        padding-top: 100%;
        position: relative;
        background-size: cover;
        background-position: center;
    }
    #notreHistoire section .articleTitleContainer {
        transform: translateY(100px);
        position: absolute;
        bottom:0;
        left:0;
        right:0;
        margin:auto;
        width:200px;
        height: 200px;
        background-color:#e7ebe8;
        display:flex;
        align-items: center;
        justify-content: center;
        align-content: center;
        flex-wrap: wrap;
    }
    #notreHistoire section article:nth-of-type(2) .articleTitleContainer {
        background-color:#e2e7e2;
    }    
    #notreHistoire section article:nth-of-type(3) .articleTitleContainer {
        background-color:#dbe2db;
    }  
    #notreHistoire section article:nth-of-type(4) .articleTitleContainer {
        background-color:#d4dcd4;
    }
    #notreHistoire section article:nth-of-type(5) .articleTitleContainer {
        background-color:#cdd6cd;
    }
    #notreHistoire section article:nth-of-type(6) .articleTitleContainer {
        background-color:#bdc8bd;
    }

    #notreHistoire section .articleDate {
        text-align:left;
        width: 100%;
        position: relative;
        padding:1rem;
        font-size: 1.6rem;
        font-weight: 700;
         text-transform: uppercase;
    }    
    #notreHistoire section .articleTitle {
        text-align:left;
        width: 100%;
        padding:1rem;
        font-size: 1.3rem;
        text-transform: uppercase;
    }     
    #notreHistoire section .articleDate:after {
        content:'';
        display: block;
        height:3px;
        width:40px;
        background-color:#91948e;
        position: absolute;
        bottom:-2px;
        left:10px;
    }      
    #notreHistoire section .articleDate:before {
        content:'';
        display: block;
        height:3px;
        width:40px;
        background-color:#91948e;
        position: absolute;
        top:-2px;
        left:10px;
    }   
    #notreHistoire section .articleText {
        padding-top: 110px;
        text-align: justify
    }    
</style>

<div id="notreHistoire">
    
    <section>
      <!--  <h3 class='titleUnderline'>Notre Histoire</h3>-->
       
       <?php 
        if(isset($_GET["lang"])) {
            if($_GET["lang"] == 'en') {
                $historyText1 = "1927";
                $historyText2 = "Once upon a time…";
                $historyText3 = "Auguste François founded his company in 1927, at a time when the Belgian textile industry was thriving and famous. Leuze was then an important textile manufacturing area, employing more than 35,000 persons. The family business knitted baby clothes that it was selling to wholesalers and retail chains, first in Belgium and later abroad.";
                $historyText4 = "Mid-1980s ";
                $historyText5 = "New generation";
                $historyText6 = "In the mid-1980s, while the Belgian textile industry was falling apart because of the virtually omnipresent Asian competition, TRICO-FRANCOIS entered into a transformation phase. Under the leadership of Liliane Francois, Auguste’s granddaughter, the family business relocated a part of its production in Cyprus to ensure the company’s future.";
                $historyText7 = "Late 1980s";
                $historyText8 = "The beginning of a new era";
                $historyText9 = "Towards the end of the 1980s, Thierry Dubus, Liliane Francois’s son, decided to embark on this adventure too. Facing an increasingly competitive market, TRICO-FRANCOIS decided to make a 360 degree turn. The textile factory would be shut down and the company would now use Polish subcontractors to produce cut and stitched articles.";
                $historyText10 = "1995";
                $historyText11 = "A change of course";
                $historyText12 = "The 80s marked a turning point in the history of the company, the real change of course happened in the 90s. In 1995, Martine Marcelle, Thierry Dubus’s wife, joined the family business. Under her impetus, TRICO-FRANCOIS decided to take on a significant challenge : rethinking completely its core business and entering the women’s ready-to-wear market. The new TRICO-FRANCOIS was born !";
                $historyText13 = "2007 ";
                $historyText14 = "The creation of a brand : MES SŒURS & MOI";
                $historyText15 = "In 2007, Thierry and Martine Dubus, wishing to stand on their own and taking advantage of their experience, decided to create their own brand of women’s ready-to-wear : MES SŒURS & MOI. Their very first collection was launched shortly after and presented at the Paris fashion trade show. ";
                $historyText16 = "2020 ";
                $historyText17 = "A family business 2.0";
                $historyText18 = "2020 will also provide many innovations. Dedicated to meet its client needs in the midst of the Covid-19 crisis, MES SŒURS & MOI will go online. As a true showcase for our collection, the e-shop will now make the ordering process easy for our partner shops and give us a real tool for direct sales. A must in a digital world.";
            }
        } 
        else {
                $historyText1 = "1927";
                $historyText2 = "Il était une fois…";
                $historyText3 = "C’est en 1927, alors que la bonneterie belge est en plein essor, qu’Auguste Francois fonde sa société. A l’époque, Leuze est un bassin textile important employant près de 35.000 personnes. L’entreprise familiale tricote des layettes (vêtements pour bébés) qu’elle vend aux grossistes et grandes chaînes en Belgique d’abord, puis à l’étranger.";
                $historyText4 = "Milieu des années 80";
                $historyText5 = "La relève";
                $historyText6 = "Milieu des années 80, alors que la bonneterie belge s’effondre au profit d’une concurrence asiatique quasi omniprésente, TRICO-FRANCOIS entame sa transformation. Rejoint par Liliane Francois, petite fille du fondateur, l’entreprise familiale délocalise une partie de sa production à Chypre, un gage de pérennité pour la société.";
                $historyText7 = "Fin des années 80";
                $historyText8 = "Le début d’une nouvelle ère";
                $historyText9 = "Fin des années 80, Thierry Dubus, fils de Liliane Francois, tente à son tour l’aventure. Face à un marché toujours plus concurrentiel, TRICO-FRANCOIS opère un virage à 360°. L’activité bonnetière est totalement mise à l’arrêt au profit du coupé/cousu dont la production est confiée à des sous-traitants polonais.";
                $historyText10 = "1995";
                $historyText11 = "Un changement de cap";
                $historyText12 = "Si les années 80 marquèrent un tournant considérable dans l’histoire de l’entreprise, les années 90 ne la laissèrent pas en reste. En 1995, l’épouse de Thierry Dubus, Martine Marcelle, rejoint la société familiale. Sous son impulsion, TRICO-FRANCOIS se lance un défi de taille : repenser totalement son core business et conquérir le marché du prêt-à-porter féminin. TRICO-FRANCOIS nouvelle génération est né !";
                $historyText13 = "2007 ";
                $historyText14 = "Une affaire de famille 2.0";
                $historyText15 = "En 2007, forts de leur expérience et avides de liberté, Thierry et Martine Dubus franchissent un nouveau cap avec la création de leur propre marque de prêt-à-porter féminin : MES SŒURS & MOI. Leur toute première collection verra le jour peu de temps après et sera présentée avec succès au salon du prêt-à-porter de Paris.  ";
                $historyText16 = "2020 ";
                $historyText17 = "Une entreprise familiale 2.0";
                $historyText18 = "2020 apporte également son lot d’innovations. Soucieuse de répondre aux besoins de ses clients B2B et encouragée par la crise sanitaire (COVID-19), MES SŒURS & MOI étend son influence en ligne. Véritable vitrine pour l’entreprise, l’e-shop lui permet dorénavant de faciliter la prise de commande des boutiques partenaires. Un must dans l’ère digitale.";
            }
        
        
        ?>
       
        <article>
            <div class='articlePicture' style='background-image:url(https://www.messoeursetmoi.be/wp-content/uploads/2020/07/1927.jpg)'>
                <div class='articleTitleContainer'>
                    <div class='articleDate'><?=$historyText1?></div>
                    <div class='articleTitle'><?=$historyText2?></div>
                </div>
            </div>
            <div class='articleText'>
                <p><?=$historyText3?></p>
            </div>
        </article>
       
        <article>
            <div class='articlePicture' style='background-image:url(https://www.messoeursetmoi.be/wp-content/uploads/2020/07/milieu-80.jpg)'>
                <div class='articleTitleContainer'>
                    <div class='articleDate'><?=$historyText4?> </div>
                    <div class='articleTitle'><?=$historyText5?></div>
                </div>
            </div>
            <div class='articleText'>
                <p><?=$historyText6?></p>
            </div>
        </article>
       
        <article>
            <div class='articlePicture' style='background-image:url(https://www.messoeursetmoi.be/wp-content/uploads/2020/07/annees-80-scaled.jpg)'>
                <div class='articleTitleContainer'>
                    <div class='articleDate'><?=$historyText7?>  </div>
                    <div class='articleTitle'><?=$historyText8?></div>
                </div>
            </div>
            <div class='articleText'>
                <p><?=$historyText9?></p>
            </div>
        </article>
       
        <article>
            <div class='articlePicture' style='background-image:url(https://www.messoeursetmoi.be/wp-content/uploads/2020/07/1995-scaled.jpg)'>
                <div class='articleTitleContainer'>
                    <div class='articleDate'><?=$historyText10?></div>
                    <div class='articleTitle'><?=$historyText11?></div>
                </div>
            </div>
            <div class='articleText'>
                <p><?=$historyText12?></p>
            </div>
        </article>
       
        <article>
            <div class='articlePicture' style='background-image:url(https://www.messoeursetmoi.be/wp-content/uploads/2020/07/2007.jpg)'>
                <div class='articleTitleContainer'>
                    <div class='articleDate'><?=$historyText13?></div>
                    <div class='articleTitle'><?=$historyText14?></div>
                </div>
            </div>
            <div class='articleText'>
                <p><?=$historyText15?></p>
            </div>
        </article>
        
       
        <article>
            <div class='articlePicture' style='background-image:url(https://www.messoeursetmoi.be/wp-content/uploads/2020/07/2020-scaled.jpg)'>
                <div class='articleTitleContainer'>
                    <div class='articleDate'><?=$historyText16?> </div>
                    <div class='articleTitle'><?=$historyText17?></div>
                </div>
            </div>
            <div class='articleText'>
                <p><?=$historyText18?></p>
            </div>
        </article>
        
    </section>
</div>


<?php
get_footer();
?>