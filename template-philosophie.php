<?php
/*
 * Template Name: Page Philosophie
 */

get_header();
?>


<style>
/* NOTRE HISTOIRE */
    #philosophie section {
        max-width: 82.75862em;
        margin:auto;
        display:flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: stretch;
        align-content: stretch;
    }   
    #philosophie .titleUnderline {
        width:100%;
        position: relative;
    }   
    #philosophie .titleUnderline:after {
        content:'';
        display: block;
        height:5px;
        width:150px;
        background-color:grey;
        position: absolute;
        bottom:-2px;
        left:0;
    }    

    #philosophie section article{
        flex-basis: 100%;
        padding:4rem 1rem;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    } 

    #philosophie section article > div{
        flex-basis: 100%;
    } 
    
    @media screen and (min-width:1024px){
        #philosophie section article > div{
            flex-basis: 50%;
        }  
        
        
        #philosophie section article:nth-last-of-type(odd){
            flex-direction: row-reverse;
        }         
        #philosophie section article:nth-last-of-type(odd) .articlePicture{
            background-position: right;
        } 

    }
    
    #philosophie section .articlePicture {
        width: 100%;
        position: relative;
        background-size: contain;
        background-position: left;
        background-repeat: no-repeat;
        min-height: 300px;
    }    
    
    

    
    
    #philosophie section .articleTitleContainer {
        margin:auto;
        
        display:flex;
        align-items: center;
        justify-content: center;
        align-content: center;
        flex-wrap: wrap;
    }

    #philosophie section .articleDate {
        width: 100%;
        position: relative;
        padding:1rem;
        font-size: 1.6rem;
        font-weight: 700;
         text-transform: uppercase;
    }    
    #philosophie section .articleTitle {
        position: relative;
        width: 100%;
        padding:1rem;
        font-size: 1.3rem;
        text-transform: uppercase;
  
    }    
    #philosophie section .articleText {
        padding:1rem;
        text-align: justify;
    }     
    #philosophie section .articleTitle:after {
        content:'';
        display: block;
        height:4px;
        width:95px;
        background-color:#edecec;
        position: absolute;
        bottom:-2px;
        left:10px;
    }      
/*    #philosophie section .articleTitle:before {
        content:'';
        display: block;
        height:2px;
        width:75px;
        background-color:black;
        position: absolute;
        top:-2px;
        left:10px;
    }*/   
    
    .mxWidth {
        max-width: 82.75862em;
        margin:auto;
    }
    .bgGrey {
        padding:3rem 1rem;
        background-color:#d6d6d6;
    }    
    .bgGrey p{
        max-width: 82.75862em;
        margin:auto;
        text-align: center;
    }
</style>

<div id="philosophie">
<!--    <div class='mxWidth'>
        <h3 class='titleUnderline'>Notre Philosophie</h3>
    </div>-->
       <?php 
            if(isset($_GET["lang"])) {
                if($_GET["lang"] == 'en') {
                $philosophyText1 = "Natural materials, socially-responsible production, fashion that is both ethical and affordable. <br/><br/>At MES SŒURS & MOI these values have guided us since the beginning. The combination of  Inheriting a textile business dating back to the 1920s,<br/> & launching our new brand in 2007, has all  been driven by the strong willingness to offer an alternative fashion for the woman of today … and of tomorrow.";
                $philosophyText2 = "Comfort & Freedom";
                $philosophyText3 = "Strong values for strong women ! MES SŒURS & MOI is intended for active and optimistic women, looking for comfortable clothing without giving up femininity, but above all for those who want to break free of a too standardized fashion. ";
                $philosophyText3_1 = "The woman, according to MES SŒURS & MOI, is free and wants to create her own personal style by combining, matching and superimposing different pieces of clothes.";
                $philosophyText4 = "Natural materials";
                $philosophyText5 = "<p>Linen, cotton, knitwear, wool, these are definitely our essential materials ! Whether light or warm, these natural materials combine comfort and elegance.</p><p> In the summer, MES SŒURS & MOI mainly uses cotton and especially linen, whose elegance is no longer to be proven.</p><p> For the winter collection, wool and knitwear, soft and warm, are used in many different ways.</p> ";
                $philosophyText6 = "Ethical production";
                $philosophyText7 = "<p>Fashion design, pattern making and model designs are made in Leuze, in our workshops built in the late 1920’s, while production is carried out abroad. Most of our collections are produced in a Tunisian workshop with which we have been working for more than 15 years.</p><p> We can also count on other strong partnerships with Portugal and Romania, famous for their knitwear.</p> ";
                $philosophyText8 = "At MES SŒURS & MOI, the quality and transparency of our relationships go hand in hand with the expertise and advice we share. As far as our suppliers are concerned, we rely on long-lasting partnerships that respect both nature and people.";
            }
        }
            else {
                $philosophyText1 = "Des matières naturelles, une fabrication responsable, et si la mode se voulait éthique et abordable ?<br/><br/> Chez MES SŒURS & MOI ces valeurs nous guident depuis nos débuts. Héritière d’un passé bonnetier propre aux années 20,<br/> notre marque a vu le jour en 2007, galvanisée par l’envie de proposer une mode alternative aux femmes d’aujourd’hui et de demain. ";
                $philosophyText2 = "Confort & liberté ";
                $philosophyText3 = "Des valeurs fortes pour des femmes qui le sont tout autant ! MES SŒURS & MOI s’adresse aux femmes actives et optimistes, à celles qui recherchent le confort autant que la féminité mais surtout à celles qui s’affranchissent des codes d’une mode trop standardisée.";
                $philosophyText3_1 = "La femme selon MES SŒURS & MOI est une femme libre qui associe, conjugue et décale chacune de ses pièces pour créer un look unique et personnel.";
                
                $philosophyText4 = "Matières naturelles";
                $philosophyText5 = "<p>Lin, coton, maille, laine, tels sont nos essentiels! Tantôt légères ou chaleureuses, ces matières naturelles se rejoignent en termes de confort et d’esthétique.</p><p> L’été, MES SŒURS & MOI fait la part belle au coton et plus particulièrement au lin dont l’élégance n’est plus à prouver.</p><p> L’hiver quant à lui décline de mille façons la laine et la maille, qui apportent chaleur et douceur.</p> ";
                $philosophyText6 = "Fabrication éthique";
                $philosophyText7 = "<p>Si le stylisme, le modélisme et les prototypes sont imaginés et conçus à Leuze, dans les ateliers familiaux aménagés fin des années 20, la production est quant à elle réalisée à l’étranger. La majorité de nos collections sont produites dans un atelier tunisien avec lequel nous collaborons depuis plus de 15 ans.</p><p> Nous pouvons également compter sur d’autres partenariats solides avec le Portugal et la Roumanie pour qui la maille n’a plus de secret.</p> ";
                $philosophyText8 = "Chez MES SŒURS & MOI, la qualité et la transparence de nos relations vont de pair avec l’expertise et les conseils que nous échangeons. Attentifs à nos fournisseurs, nous misons sur des partenariats durables et respectueux tant de la nature que des hommes.";
            }
       
       ?>
       
       
        <div class='bgGrey'>
            <p>
                <?=$philosophyText1?>
            </p>
        </div>
    <section>
        <article>
            <div class='articlePicture' style='background-image:url(https://www.messoeursetmoi.be/wp-content/uploads/2020/07/Philosophy-element01.png)'>
            </div>
            <div>
                <div class='articleTitleContainer'>
                    <div class='articleTitle'> <?=$philosophyText2?></div>
                </div>
                <div class='articleText'>
                    <p> <?=$philosophyText3?></p>
                    <p> <?=$philosophyText3_1?></p>
                </div>
            </div>
        </article>       
        <article>
            <div class='articlePicture' style='background-image:url(https://www.messoeursetmoi.be/wp-content/uploads/2020/07/Philosophy-element02.png)'>
            </div>
            <div>
                <div class='articleTitleContainer'>
                    <div class='articleTitle'> <?=$philosophyText4?></div>
                </div>
                <div class='articleText'>
                    <?=$philosophyText5?>
                </div>
            </div>
        </article>       
        <article>
            <div class='articlePicture' style='background-image:url(https://www.messoeursetmoi.be/wp-content/uploads/2020/07/Philosophy-element03.png)'>
            </div>
            <div>
                <div class='articleTitleContainer'>
                    <div class='articleTitle'> <?=$philosophyText6?></div>
                </div>
                <div class='articleText'>
                    <?=$philosophyText7?>
                    <p>
                     <?=$philosophyText8?>
                    </p>
                </div>
            </div>
        </article>
       
        
    </section>
</div>


<?php
get_footer();
?>