#!/usr/bin/python
from Bowling import Bowling

game = Bowling()

for i in range(game.TURNS * game.TRIES + 1): game.play()
