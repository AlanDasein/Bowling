import java.util.Arrays;

public class Bowling {
    
    // constants
    public static final int PINS = 10;
    public static final int TURNS = 10;
    public static final int TRIES = 2;
    
    // variables
    private int current_turn = 0;
    private int current_try = -1;
    private int[][] played_turns = new int[TURNS][TRIES + 1];
    
    // methods
    public void play() {
        for(int i = 0;i < TURNS * TRIES + 2;i++) {
            if(gameCompleted()) {
                getScore();
                break;
            }
            setNextRoll();
            roll();
        }
    }
    
    private void getScore() {
        int total = 0, score;
        String output;
        for(int i = 0;i < TURNS;i++) {
            score = 0;
            output = "TURN " + (i < 9 ? "0" + (i + 1) : i + 1) + ": (";
            for(int j = 0;j < TRIES + 1;j++) {
                if(j < TRIES || (i == TURNS - 1 && (isStrike(i, 0) || isStrike(i, 1) || isSpare(i)))) {
                    score += played_turns[i][j];
                    output += (played_turns[i][j] + (", "));
                }
            }
            if(i < TURNS - 1) {
                if(isStrike(i, 0)) {
                    score += (played_turns[i + 1][0] + played_turns[i + 1][1]);
                    if(isStrike(i + 1, 0) && i < TURNS - 2) score += played_turns[i + 2][0];
                }
                else if(isSpare(i)) score += played_turns[i + 1][0];
            }
            total += score;
            output = (output.substring(0, output.length() - 2) + ") " + "(turn score: " + score + ") (total score: " + total + ")");
            System.out.println(output);
        }
    }
    
    private void roll() {
        int pins_left = PINS;
        if(current_try > 0 && (current_turn < TURNS - 1 || !isStrike(current_turn, current_try - 1))) {
            pins_left -= played_turns[current_turn][current_try - 1];
        }
        played_turns[current_turn][current_try] = (int)Math.round(Math.random() * pins_left);
    }
    
    private void setNextRoll() {
        current_try++;
        if(current_try == TRIES && (current_turn < TURNS - 1 || !extraTry())) {
            current_try = 0;
            current_turn++;
        }
    }
    
    private boolean gameCompleted() {
        return current_turn == TURNS - 1 && (current_try == TRIES || current_try > 0 && !extraTry());
    }
    
    private boolean extraTry() {
        return isStrike(current_turn, 0) || isStrike(current_turn, current_try - 1) || isSpare(current_turn);
    }
    
    private boolean isStrike(int _turn, int _try) {
        return played_turns[_turn][_try] == PINS;
    }
    
    private boolean isSpare(int _turn) {
        return played_turns[_turn][0] + played_turns[_turn][1] == PINS;
    }
    
}