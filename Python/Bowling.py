import random

class Bowling:

    # constants
    PINS = 10
    TURNS = 10
    TRIES = 2

    # private variables
    __current_turn = 0
    __current_try = 0
    __played_turns = []

    # main function
    def play(self):
        self.__setTry()
        self.__setTurn()
        self.__roll()
        self.__getScore()

    # private methods
    def __getScore(self):
        if self.__gameCompleted():
            total = 0
            for i in range(len(self.__played_turns)):
                score = 0
                output = "TURN " + ("0" + str(i + 1) if i < 9 else str(i + 1)) + ": " + str(self.__played_turns[i])
                for val in self.__played_turns[i]: score += val
                if(i < self.TURNS - 1):
                    if self.__isStrike(i):
                        score += (self.__played_turns[i + 1][0] + self.__played_turns[i + 1][1])
                        if self.__isStrike(i + 1) and i < self.TURNS - 2: score += self.__played_turns[i + 2][0]
                    elif self.__isSpare(i): score += self.__played_turns[i + 1][0]
                total += score
                output += ("(turn score: " + str(score) + ") (total score: " + str(total) + ")")
                print(output)

    def __setTry(self):
        if self.__current_try < self.TRIES: self.__current_try += 1
        else: self.__current_try = self.__current_try + 1 if self.__extraTry() else 1
        
    def __setTurn(self):
        if self.__current_turn < self.TURNS and self.__current_try == 1:
            self.__played_turns.append([])
            self.__current_turn += 1
        
    def __roll(self):
        pins_left = self.PINS
        if self.__current_try >= self.TRIES and (self.__current_turn < self.TURNS or (self.__current_try == self.TRIES and not self.__isStrike(self.__current_turn - 1)) or (self.__current_try > self.TRIES and (not self.__isStrike(self.__current_turn - 1, self.__current_try - 2) or not self.__isSpare(self.__current_turn - 1)))):
            pins_left -= self.__played_turns[self.__current_turn - 1][self.__current_try - 2]
        self.__played_turns[self.__current_turn - 1].append(random.randint(0, pins_left))
        
    def __gameCompleted(self):
        return self.__current_turn == self.TURNS and (self.__current_try > self.TRIES or (self.__current_try == self.TRIES and not self.__extraTry()))
    
    def __extraTry(self):
        return self.__current_turn == self.TURNS and self.__current_try == self.TRIES and (self.__isStrike(self.__current_turn - 1) or self.__isSpare(self.__current_turn - 1))
    
    def __isStrike(self, this_turn, this_try = 0):
        return self.__played_turns[this_turn][this_try] == self.PINS
    
    def __isSpare(self, this_turn):
        return self.__played_turns[this_turn][0] + self.__played_turns[this_turn][1] == self.PINS
