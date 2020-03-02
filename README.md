# Genetic

Welcome to Genetic, an app running tournament-based genetic algorithms.
This console app is mainly based on the component symfony/console.
It is tested with PHPUnit.

# Purpose

Genetic algorithms are commonly used to generate high-quality solutions to optimization and search problems 
by relying on biologically inspired operators such as mutation, crossover and selection.
This app can simulate the Darwin's theory and find the best creature in a randomly-generated population using iteration process :
* tournament (competition-based, multiple fights)
* selection
* crossover 
* and mutation

This app is paticular as it could use gene with different costs and uses an innovative fitness algorithm.

# Examples

Configuring a genetic algorithms could be somewhat tediuos so I leave 3 samples in this app.
Feel free to erase them as you wish. To make a real life challenge I simulate 3 roleplaying game rules
and select the best character for fight. Of course not all rules are implemented (and need too many genes)

## L5r namespace

It simulate the Legend of the Five Rings. In a character, the are gene to simulate traits and skills of course but also void spending stategy ans stances.

## SaWo namespace

Savage Worlds Deluxe Edition rules. It simulate melee fights between characters with Edge such as Trademark weapon and Block. It does not 
implement Initiative card.

## Vda namespace

Vampire Dark Age 20th anniversary. Meele fights with Potence, Celerity and Fortitude. Blood Pool is not simulated.

# How I add my game ?

You have 3 main tasks :
* create a new Command by subclassing GameFree (a Template Method pattern)
* create a new Character by subclassing MutableFighter. Genes are created by implementing Property interface or subclassing CappedProperty abstract class
* create a new evolution "world" by subclassing DarwinWorld (mainly initiative rules). It contains all game master decision process.

# Printing solutions

This app uses 4 different loggers : TextLogger for printing creature, GrafxLogger for viewing the population graphically, 
StatLogger to estimate if the evolution process is convergent or not. 

# Results

Here's an example with L5r. Running 200 iterations with 1000 characters.

```
php app.php l5r:free --extinct=1 --round=3 --plot=l5r --animate --stat --dump 1000 200
```

This command means 1% is extinct and replace each iteration.
3 rounds are run to estimate which is the best fighter between two.
--plot is the prefix name for image plotting.
--animate option generates multiple images
--stat option prints statistics and creates a CSV file
1000 creatures
200 iterations

Horizontal axis is the genome cost and vertical axis is the victory count for each creatures :
![Evolution of population](/doc/l5r-200.gif)

The evolution of average cost for the best creatures (10% of all victories) :
![Convergent cost](/doc/l5r-conv.png)

Final iteration :
```
0 - agility:4 kenjutsu:5 void:2 reflexe:5 earth:2 voidStrat:soak stance:full strength:2 win:445 cost:90
1 - agility:4 kenjutsu:3 void:2 reflexe:5 earth:2 voidStrat:soak stance:full strength:2 win:443 cost:81
2 - agility:4 kenjutsu:4 void:2 reflexe:5 earth:2 voidStrat:armor stance:standard strength:2 win:434 cost:85
3 - agility:5 kenjutsu:3 void:2 reflexe:5 earth:2 voidStrat:soak stance:standard strength:2 win:433 cost:101
4 - agility:4 kenjutsu:3 void:2 reflexe:5 earth:2 voidStrat:soak stance:standard strength:2 win:432 cost:81
5 - agility:4 kenjutsu:4 void:2 reflexe:5 earth:2 voidStrat:armor stance:standard strength:2 win:430 cost:85
```