#!/usr/bin/env node

"use strict";

+function() {

    var Bowling = {

        // constants
        cons: {
            PINS: 10,
            TURNS: 10,
            TRIES: 2,
        },

        // variables
        vars: {
            current_turn: 0,
            current_try: 0,
            played_turns: [],
        },

        // functions
        funcs: {

            // main function
            play: function () {
                Bowling.funcs.internal.setTry();
                Bowling.funcs.internal.setTurn();
                Bowling.funcs.internal.roll();
                Bowling.funcs.internal.getScore();
            },

            // internal functions
            internal: {

                getScore: function() {
                    if(Bowling.funcs.utils.gameCompleted()) {
                        var total = 0, score, output;
                        console.log();
                        for(let i = 1;i < Bowling.cons.TURNS + 1;i++) {
                            output = "";
                            score = 0;
                            output += ("TURN " + (i < 10 ? "0" + i : i) + ": (" + Bowling.vars.played_turns[i].slice(1).join(", ") + ") ");
                            for(let j = 1, k = Bowling.vars.played_turns[i].length;j < k;j++) score += Bowling.vars.played_turns[i][j];
                            if(i < Bowling.cons.TURNS) {
                                if(Bowling.funcs.utils.isStrike(i)) {
                                    score += (Bowling.vars.played_turns[i + 1][1] + Bowling.vars.played_turns[i + 1][2]);
                                    if(Bowling.funcs.utils.isStrike(i + 1) && i < Bowling.cons.TURNS - 1) {
                                        score += Bowling.vars.played_turns[i + 2][1];
                                    }
                                }
                                else if(Bowling.funcs.utils.isSpare(i)) score += Bowling.vars.played_turns[i + 1][1];
                            }
                            total += score;
                            output += ("(turn score: " + score + ") (total score: " + total + ")");
                            console.log(output);
                        }
                        console.log();
                    }
                },

                setTry: function() {
                    if(Bowling.vars.current_try < Bowling.cons.TRIES) Bowling.vars.current_try++;
                    else Bowling.vars.current_try = Bowling.funcs.utils.extraTry() ? (Bowling.vars.current_try + 1) : 1;
                },

                setTurn: function() {
                    if(Bowling.vars.current_turn < Bowling.cons.TURNS && Bowling.vars.current_try === 1) {
                        Bowling.vars.played_turns[++Bowling.vars.current_turn] = [];
                    }
                },
                
                roll: function() {
                    var pins_left = Bowling.cons.PINS;
                    if(
                        Bowling.vars.current_try >= Bowling.cons.TRIES &&
                        (
                            Bowling.vars.current_turn < Bowling.cons.TURNS ||
                            (Bowling.vars.current_try === Bowling.cons.TRIES && !Bowling.funcs.utils.isStrike(Bowling.vars.current_turn)) ||
                            (
                                Bowling.vars.current_try > Bowling.cons.TRIES &&
                                (
                                    !Bowling.funcs.utils.isStrike(Bowling.vars.current_turn, Bowling.vars.current_try - 1) ||
                                    !Bowling.funcs.utils.isSpare(Bowling.vars.current_turn)
                                )
                            )
                        )
                    ) {pins_left -= Bowling.vars.played_turns[Bowling.vars.current_turn][Bowling.vars.current_try - 1];}
                    Bowling.vars.played_turns[Bowling.vars.current_turn][Bowling.vars.current_try] = Math.round(Math.random() * pins_left);
                },

            },

            // utility functions
            utils: {
                
                gameCompleted: function() {
                    return (
                        Bowling.vars.current_turn === Bowling.cons.TURNS &&
                        (Bowling.vars.current_try > Bowling.cons.TRIES || (Bowling.vars.current_try === Bowling.cons.TRIES && !Bowling.funcs.utils.extraTry()))
                    );
                },

                extraTry: function() {
                    return (
                        Bowling.vars.current_turn === Bowling.cons.TURNS && Bowling.vars.current_try === Bowling.cons.TRIES &&
                        (Bowling.funcs.utils.isStrike(Bowling.vars.current_turn) || Bowling.funcs.utils.isSpare(Bowling.vars.current_turn))
                    );
                },

                isStrike: function(_turn, _try) {
                    _try = _try || 1;
                    return Bowling.vars.played_turns[_turn][_try] === Bowling.cons.PINS;
                },

                isSpare: function(_turn) {
                    return Bowling.vars.played_turns[_turn][1] + Bowling.vars.played_turns[_turn][2] === Bowling.cons.PINS;
                },

            }

        },

    };

    for(let i = 0, j = (Bowling.cons.TURNS * Bowling.cons.TRIES) + 1;i < j;i++) Bowling.funcs.play();

}();