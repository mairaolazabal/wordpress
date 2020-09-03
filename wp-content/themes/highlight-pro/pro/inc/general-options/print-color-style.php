<?php foreach ($textElements as $element): ?>
    <?php echo "{$element}{$colorClass}" ?>{
    color : <?php echo $color; ?>;
    }
<?php endforeach; ?>

.card.bg-<?php echo $colorName; ?>,
.bg-<?php echo $colorName; ?>{
/* */background-color:<?php echo $color ?>;
}

a<?php echo $colorClass; ?>:not(.button){
/* */color:<?php echo $color; ?>;
}

a<?php echo $colorClass; ?>:not(.button):hover{
/* */color:<?php echo $hoverColor; ?>;
}

button<?php echo $colorClass; ?>,
.button<?php echo $colorClass; ?>{
/* */background-color:<?php echo $color; ?>;
/* */border-color:<?php echo $color; ?>;
}

button<?php echo $colorClass; ?>:hover,
.button<?php echo $colorClass; ?>:hover{
/* */background-color:<?php echo $hoverColor; ?>;
/* */border-color:<?php echo $hoverColor; ?>;
}

button.outline<?php echo $colorClass; ?>,
.button.outline<?php echo $colorClass; ?>{
/* */background:none;
/* */border-color:<?php echo $color; ?>;
/* */color:<?php echo $color; ?>;
}

button.outline<?php echo $colorClass; ?>:hover,
.button.outline<?php echo $colorClass; ?>:hover{
/* */background:none;
/* */border-color:<?php echo \Kirki_Color::get_rgba($color, 0.7); ?>;
/* */color:<?php echo \Kirki_Color::get_rgba($color, 0.9); ?>;
}


i.fa<?php echo $colorClass ?>{
/* */color:<?php echo $color; ?>;
}


i.fa.icon.bordered<?php echo $colorClass ?>{
/* */border-color:<?php echo $color; ?>;
}

i.fa.icon.reverse<?php echo $colorClass ?>{
/* */background-color:<?php echo $color; ?>;
/* */color: #ffffff;
}

i.fa.icon.reverse.color-white{
/* */color: #d5d5d5;
}

i.fa.icon.bordered<?php echo $colorClass ?>{
/* */border-color:<?php echo $color; ?>;
}

i.fa.icon.reverse.bordered<?php echo $colorClass ?>{
/* */background-color:<?php echo $color; ?>;
/* */color: #ffffff;
}

.top-right-triangle<?php echo $colorClass ?>{
/* */border-right-color:<?php echo $color; ?>;
}
.checked.decoration-<?php echo $colorName; ?> li:before {
/* */color:<?php echo $color; ?>;
}

.stared.decoration-<?php echo $colorName; ?> li:before {
/* */color:<?php echo $color; ?>;
}

.card.card-<?php echo $colorName; ?>{
/* */background-color:<?php echo $color; ?>;
}


.card.bottom-border-<?php echo $colorName; ?>{
/* */border-bottom-color: <?php echo $color; ?>;
}

.grad-180-transparent-<?php echo $colorName; ?>{
/* */ background-image: linear-gradient(180deg, <?php echo \Kirki_Color::get_rgba($color, 0); ?> 0%, <?php echo \Kirki_Color::get_rgba($color, 0); ?> 50%, <?php echo \Kirki_Color::get_rgba($color, 0.6); ?> 78%, <?php echo \Kirki_Color::get_rgba($color, 0.9); ?> 100%) !important;
}

.border-<?php echo $colorName; ?>{
/* */border-color: <?php echo $color; ?>;
}

.circle-counter.<?php echo $colorName; ?> .circle-bar{
/* */stroke: <?php echo $color; ?>;
}
