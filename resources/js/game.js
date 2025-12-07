// Connect Four Game Logic
class ConnectFourGame {
    constructor() {
        this.ROWS = 6;
        this.COLS = 7;
        this.PLAYER = 1;
        this.AI = 2;
        this.board = [];
        this.currentPlayer = this.PLAYER;
        this.difficulty = null;
        this.gameOver = false;
        this.turnCount = 0;
        this.winningCells = [];
        this.lastDroppedCell = null; // Track the last dropped disc position

        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Difficulty selection
        document.querySelectorAll('.difficulty-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.startGame(e.target.dataset.difficulty);
            });
        });

        // Reset button
        const resetBtn = document.getElementById('resetButton');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => this.reset());
        }
    }

    startGame(difficulty) {
        this.difficulty = difficulty;
        this.board = Array(this.ROWS).fill(null).map(() => Array(this.COLS).fill(0));
        this.currentPlayer = this.PLAYER;
        this.gameOver = false;
        this.turnCount = 0;
        this.winningCells = [];
        this.lastDroppedCell = null;

        // Show game board, hide difficulty selection
        document.getElementById('difficultySelection').style.display = 'none';
        document.getElementById('gameBoardContainer').style.display = 'block';
        document.getElementById('highScoreForm').style.display = 'none';
        document.getElementById('gameMessage').style.display = 'none';

        // Update UI
        document.getElementById('selectedDifficulty').textContent = difficulty;
        document.getElementById('turnCount').textContent = '0';

        this.renderBoard();
    }

    renderBoard() {
        const boardElement = document.getElementById('gameBoard');
        boardElement.innerHTML = '';

        // Create cells (top to bottom, left to right)
        for (let row = 0; row < this.ROWS; row++) {
            for (let col = 0; col < this.COLS; col++) {
                const cell = document.createElement('div');
                cell.className = 'cell';
                cell.dataset.row = row;
                cell.dataset.col = col;

                // Add click listener for column
                if (!this.gameOver) {
                    cell.addEventListener('click', () => this.handleCellClick(col));
                }

                // Add disc if present
                if (this.board[row][col] !== 0) {
                    const disc = document.createElement('div');
                    disc.className = `disc ${this.board[row][col] === this.PLAYER ? 'player' : 'ai'}`;

                    // Only animate the last dropped disc
                    const isLastDropped = this.lastDroppedCell &&
                        this.lastDroppedCell.row === row &&
                        this.lastDroppedCell.col === col;
                    if (!isLastDropped) {
                        disc.classList.add('no-animation');
                    }

                    // Highlight winning discs
                    if (this.winningCells.some(([r, c]) => r === row && c === col)) {
                        disc.classList.add('winning');
                    }

                    cell.appendChild(disc);
                    cell.classList.add('filled');
                }

                boardElement.appendChild(cell);
            }
        }
    }

    handleCellClick(col) {
        if (this.gameOver || this.currentPlayer !== this.PLAYER) {
            return;
        }

        if (this.dropDisc(col, this.PLAYER)) {
            this.turnCount++;
            document.getElementById('turnCount').textContent = this.turnCount;

            this.renderBoard();

            if (this.checkWin(this.PLAYER)) {
                this.endGame('player');
            } else if (this.checkDraw()) {
                this.endGame('draw');
            } else {
                // AI's turn
                this.currentPlayer = this.AI;
                this.updateTurnIndicator();

                setTimeout(() => {
                    this.aiMove();
                }, 1000); // Wait for player's disc animation to finish
            }
        }
    }

    dropDisc(col, player) {
        // Find lowest empty row in column
        for (let row = this.ROWS - 1; row >= 0; row--) {
            if (this.board[row][col] === 0) {
                this.board[row][col] = player;
                this.lastDroppedCell = { row, col }; // Track the dropped position
                return true;
            }
        }
        return false; // Column full
    }

    aiMove() {
        let col;

        // Determine if AI plays optimally based on difficulty
        const random = Math.random();
        let playOptimal = false;

        switch (this.difficulty) {
            case 'EASY':
                playOptimal = random < 0.66; // 66% optimal
                break;
            case 'MEDIUM':
                playOptimal = random < 0.90; // 90% optimal
                break;
            case 'HARD':
                playOptimal = true; // 100% optimal
                break;
        }

        if (playOptimal) {
            col = this.findBestMove();
        } else {
            col = this.findRandomMove();
        }

        this.dropDisc(col, this.AI);
        this.renderBoard();

        if (this.checkWin(this.AI)) {
            this.endGame('ai');
        } else if (this.checkDraw()) {
            this.endGame('draw');
        } else {
            this.currentPlayer = this.PLAYER;
            this.updateTurnIndicator();
        }
    }

    findRandomMove() {
        const validCols = [];
        for (let col = 0; col < this.COLS; col++) {
            if (this.board[0][col] === 0) {
                validCols.push(col);
            }
        }
        return validCols[Math.floor(Math.random() * validCols.length)];
    }

    findBestMove() {
        let bestScore = -Infinity;
        let bestCol = 0;

        for (let col = 0; col < this.COLS; col++) {
            if (this.board[0][col] === 0) {
                // Try this move
                const row = this.getLowestRow(col);
                this.board[row][col] = this.AI;

                const score = this.minimax(4, -Infinity, Infinity, false);

                this.board[row][col] = 0; // Undo move

                if (score > bestScore) {
                    bestScore = score;
                    bestCol = col;
                }
            }
        }

        return bestCol;
    }

    minimax(depth, alpha, beta, isMaximizing) {
        // Check terminal states
        if (this.checkWinFor(this.AI)) return 100000;
        if (this.checkWinFor(this.PLAYER)) return -100000;
        if (this.checkDraw()) return 0;
        if (depth === 0) return this.evaluateBoard();

        if (isMaximizing) {
            let maxScore = -Infinity;
            for (let col = 0; col < this.COLS; col++) {
                if (this.board[0][col] === 0) {
                    const row = this.getLowestRow(col);
                    this.board[row][col] = this.AI;

                    const score = this.minimax(depth - 1, alpha, beta, false);

                    this.board[row][col] = 0;

                    maxScore = Math.max(maxScore, score);
                    alpha = Math.max(alpha, score);
                    if (beta <= alpha) break; // Alpha-beta pruning
                }
            }
            return maxScore;
        } else {
            let minScore = Infinity;
            for (let col = 0; col < this.COLS; col++) {
                if (this.board[0][col] === 0) {
                    const row = this.getLowestRow(col);
                    this.board[row][col] = this.PLAYER;

                    const score = this.minimax(depth - 1, alpha, beta, true);

                    this.board[row][col] = 0;

                    minScore = Math.min(minScore, score);
                    beta = Math.min(beta, score);
                    if (beta <= alpha) break; // Alpha-beta pruning
                }
            }
            return minScore;
        }
    }

    evaluateBoard() {
        let score = 0;

        // Center column preference
        for (let row = 0; row < this.ROWS; row++) {
            if (this.board[row][3] === this.AI) score += 3;
            if (this.board[row][3] === this.PLAYER) score -= 3;
        }

        // Evaluate windows of 4
        score += this.evaluateWindows(this.AI);
        score -= this.evaluateWindows(this.PLAYER);

        return score;
    }

    evaluateWindows(player) {
        let score = 0;

        // Horizontal
        for (let row = 0; row < this.ROWS; row++) {
            for (let col = 0; col < this.COLS - 3; col++) {
                const window = [
                    this.board[row][col],
                    this.board[row][col + 1],
                    this.board[row][col + 2],
                    this.board[row][col + 3]
                ];
                score += this.scoreWindow(window, player);
            }
        }

        // Vertical
        for (let row = 0; row < this.ROWS - 3; row++) {
            for (let col = 0; col < this.COLS; col++) {
                const window = [
                    this.board[row][col],
                    this.board[row + 1][col],
                    this.board[row + 2][col],
                    this.board[row + 3][col]
                ];
                score += this.scoreWindow(window, player);
            }
        }

        // Diagonal (/)
        for (let row = 3; row < this.ROWS; row++) {
            for (let col = 0; col < this.COLS - 3; col++) {
                const window = [
                    this.board[row][col],
                    this.board[row - 1][col + 1],
                    this.board[row - 2][col + 2],
                    this.board[row - 3][col + 3]
                ];
                score += this.scoreWindow(window, player);
            }
        }

        // Diagonal (\)
        for (let row = 0; row < this.ROWS - 3; row++) {
            for (let col = 0; col < this.COLS - 3; col++) {
                const window = [
                    this.board[row][col],
                    this.board[row + 1][col + 1],
                    this.board[row + 2][col + 2],
                    this.board[row + 3][col + 3]
                ];
                score += this.scoreWindow(window, player);
            }
        }

        return score;
    }

    scoreWindow(window, player) {
        let score = 0;
        const opponent = player === this.PLAYER ? this.AI : this.PLAYER;

        const playerCount = window.filter(cell => cell === player).length;
        const emptyCount = window.filter(cell => cell === 0).length;
        const opponentCount = window.filter(cell => cell === opponent).length;

        if (playerCount === 4) score += 100;
        else if (playerCount === 3 && emptyCount === 1) score += 5;
        else if (playerCount === 2 && emptyCount === 2) score += 2;

        if (opponentCount === 3 && emptyCount === 1) score -= 4;

        return score;
    }

    getLowestRow(col) {
        for (let row = this.ROWS - 1; row >= 0; row--) {
            if (this.board[row][col] === 0) {
                return row;
            }
        }
        return -1;
    }

    checkWin(player) {
        return this.checkWinFor(player, true);
    }

    checkWinFor(player, recordWinningCells = false) {
        // Horizontal
        for (let row = 0; row < this.ROWS; row++) {
            for (let col = 0; col < this.COLS - 3; col++) {
                if (this.board[row][col] === player &&
                    this.board[row][col + 1] === player &&
                    this.board[row][col + 2] === player &&
                    this.board[row][col + 3] === player) {
                    if (recordWinningCells) {
                        this.winningCells = [
                            [row, col], [row, col + 1],
                            [row, col + 2], [row, col + 3]
                        ];
                    }
                    return true;
                }
            }
        }

        // Vertical
        for (let row = 0; row < this.ROWS - 3; row++) {
            for (let col = 0; col < this.COLS; col++) {
                if (this.board[row][col] === player &&
                    this.board[row + 1][col] === player &&
                    this.board[row + 2][col] === player &&
                    this.board[row + 3][col] === player) {
                    if (recordWinningCells) {
                        this.winningCells = [
                            [row, col], [row + 1, col],
                            [row + 2, col], [row + 3, col]
                        ];
                    }
                    return true;
                }
            }
        }

        // Diagonal (/)
        for (let row = 3; row < this.ROWS; row++) {
            for (let col = 0; col < this.COLS - 3; col++) {
                if (this.board[row][col] === player &&
                    this.board[row - 1][col + 1] === player &&
                    this.board[row - 2][col + 2] === player &&
                    this.board[row - 3][col + 3] === player) {
                    if (recordWinningCells) {
                        this.winningCells = [
                            [row, col], [row - 1, col + 1],
                            [row - 2, col + 2], [row - 3, col + 3]
                        ];
                    }
                    return true;
                }
            }
        }

        // Diagonal (\)
        for (let row = 0; row < this.ROWS - 3; row++) {
            for (let col = 0; col < this.COLS - 3; col++) {
                if (this.board[row][col] === player &&
                    this.board[row + 1][col + 1] === player &&
                    this.board[row + 2][col + 2] === player &&
                    this.board[row + 3][col + 3] === player) {
                    if (recordWinningCells) {
                        this.winningCells = [
                            [row, col], [row + 1, col + 1],
                            [row + 2, col + 2], [row + 3, col + 3]
                        ];
                    }
                    return true;
                }
            }
        }

        return false;
    }

    checkDraw() {
        return this.board[0].every(cell => cell !== 0);
    }

    endGame(winner) {
        this.gameOver = true;
        this.renderBoard(); // Re-render to show winning cells

        const messageElement = document.getElementById('messageText');
        const gameMessageElement = document.getElementById('gameMessage');

        if (winner === 'player') {
            messageElement.textContent = 'You Win! 🎉';
            gameMessageElement.style.display = 'block';

            // Show high score form
            document.getElementById('formTurns').value = this.turnCount;
            document.getElementById('formDifficulty').value = this.difficulty;
            document.getElementById('displayTurns').textContent = this.turnCount;
            document.getElementById('displayDifficulty').textContent = this.difficulty;
            document.getElementById('highScoreForm').style.display = 'block';
        } else if (winner === 'ai') {
            messageElement.textContent = 'AI Wins! Better luck next time.';
            gameMessageElement.style.display = 'block';
        } else {
            messageElement.textContent = "It's a Draw!";
            gameMessageElement.style.display = 'block';
        }

        this.updateTurnIndicator();
    }

    updateTurnIndicator() {
        const indicator = document.querySelector('.turn-indicator');
        if (this.gameOver) {
            indicator.innerHTML = '<span class="player-indicator">Game Over</span>';
        } else if (this.currentPlayer === this.PLAYER) {
            indicator.innerHTML = '<span class="player-indicator">Your Turn</span>';
        } else {
            indicator.innerHTML = '<span class="ai-indicator">AI is thinking...</span>';
        }
    }

    reset() {
        document.getElementById('difficultySelection').style.display = 'block';
        document.getElementById('gameBoardContainer').style.display = 'none';
        document.getElementById('highScoreForm').style.display = 'none';
        document.getElementById('gameMessage').style.display = 'none';
    }
}

// Initialize game when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    if (document.getElementById('gameBoard')) {
        window.game = new ConnectFourGame();
    }
});
