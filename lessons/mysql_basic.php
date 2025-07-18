<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MySQL Basics - SkillSynergy</title>
    <style>
      body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 0; padding: 0; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .lesson-card { display: flex; flex-direction: column; height: 100%; min-height: 100px; max-height: 400px; position: relative; }
        .lesson-scrollable { flex: 1 1 auto; overflow-y: auto; background: #f8f9fa; padding: 16px; border-radius: 0 0 0 0; margin-bottom: 0; min-height: 160px; height: auto; position: relative; max-height: 400px; }
        .lesson-section { display: none; }
        .lesson-section.active { display: block; }
        .lesson-nav { display: flex; justify-content: space-between; align-items: center; background: #fff; border-top: 5px solid #e2e8f0; padding: 16px 48px 16px 48px; box-shadow: 0 -2px 8px rgba(0,0,0,0.04); z-index: 2; flex: 0 0 auto; box-sizing: border-box; position: relative; bottom: 0; left: 0; right: 0; }
        .lesson-nav button { background: #007bff; color: white; border: none; border-radius: 4px; padding: 8px 18px; font-size: 14px; cursor: pointer; transition: background 0.2s; margin: 0 16px; }
        .lesson-nav button:disabled { background: #b0b0b0; cursor: not-allowed; }
        .lesson-nav button:hover:not(:disabled) { background: #0056b3; }
        #otherTopicsDropdown { display: none; position: absolute; bottom: 48px; right: 0; min-width: 220px; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); z-index: 10; padding: 6px 0; }
        #otherTopicsDropdown .topic-btn { display: block; width: 100%; border: none; background: none; padding: 10px 20px; text-align: left; font-size: 1rem; color: #333; cursor: pointer; transition: background 0.2s; }
        #otherTopicsDropdown .topic-btn:hover { background: #f0f4ff; color: #4a63ff; }
        .code-block { background: #2d3748; color: #e2e8f0; padding: 20px; border-radius: 8px; margin: 15px 0; overflow-x: auto; font-family: 'Courier New', monospace; font-size: 14px; line-height: 1.6; }
        .example-box { background: #e6fffa; border: 1px solid #81e6d9; border-radius: 8px; padding: 20px; margin: 15px 0; }
        .tip-box { background: #fef5e7; border: 1px solid #f6ad55; border-radius: 8px; padding: 15px; margin: 15px 0; }
        .tip-box strong { color: #c05621; }
        ul, ol { margin: 15px 0; padding-left: 30px; }
        li { margin: 8px 0; }
        strong { color: #2d3748; }
        .highlight { background: #fef5e7; padding: 2px 6px; border-radius: 4px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="lesson-card">
      <div class="lesson-scrollable">
        <div class="lesson-section active" data-index="0">
          <h2>Introduction to MySQL</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>MySQL is a widely used open-source relational database management system (RDBMS). It is known for its reliability, performance, and ease of use for managing structured data.</p>
          <h3>Why MySQL?</h3>
          <ul>
            <li><strong>Open Source:</strong> Free and widely supported</li>
            <li><strong>Reliable:</strong> Proven stability and performance</li>
            <li><strong>Scalable:</strong> Handles small to large-scale applications</li>
            <li><strong>SQL Support:</strong> Standard SQL syntax for queries</li>
            <li><strong>Community:</strong> Large ecosystem and documentation</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> MySQL is ideal for web applications and supports many programming languages.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Installation & Setup</h2>
          <p>Install MySQL on your system or use a managed service like AWS RDS or Azure Database for MySQL.</p>
          <h3>Install MySQL (Local)</h3>
          <div class="code-block">
# On Ubuntu
sudo apt-get install mysql-server

# On macOS (Homebrew)
brew install mysql
          </div>
          <h3>Start MySQL</h3>
          <div class="code-block">
# Start the MySQL service
sudo service mysql start

# Or (macOS)
brew services start mysql
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use tools like phpMyAdmin or MySQL Workbench for GUI management.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Basic SQL Operations</h2>
          <p>SQL (Structured Query Language) is used to manage and manipulate data in MySQL.</p>
          <h3>Create a Database</h3>
          <div class="code-block">
CREATE DATABASE mydb;
          </div>
          <h3>Create a Table</h3>
          <div class="code-block">
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(100)
);
          </div>
          <h3>Insert Data</h3>
          <div class="code-block">
INSERT INTO users (name, email) VALUES ('Alice', 'alice@example.com');
          </div>
          <h3>Select Data</h3>
          <div class="code-block">
SELECT * FROM users;
          </div>
          <h3>Update Data</h3>
          <div class="code-block">
UPDATE users SET name = 'Bob' WHERE id = 1;
          </div>
          <h3>Delete Data</h3>
          <div class="code-block">
DELETE FROM users WHERE id = 1;
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Joins</h2>
          <p>Joins are used to combine rows from two or more tables based on related columns.</p>
          <h3>Inner Join Example</h3>
          <div class="code-block">
SELECT users.name, orders.amount
FROM users
INNER JOIN orders ON users.id = orders.user_id;
          </div>
          <h3>Left Join Example</h3>
          <div class="code-block">
SELECT users.name, orders.amount
FROM users
LEFT JOIN orders ON users.id = orders.user_id;
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Indexes</h2>
          <p>Indexes improve the speed of data retrieval operations in MySQL.</p>
          <h3>Create an Index</h3>
          <div class="code-block">
CREATE INDEX idx_name ON users(name);
          </div>
          <h3>View Indexes</h3>
          <div class="code-block">
SHOW INDEX FROM users;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use indexes on columns that are frequently searched or used in joins.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Backup & Restore</h2>
          <p>Backing up and restoring databases is essential for data safety.</p>
          <h3>Backup Database</h3>
          <div class="code-block">
mysqldump -u username -p mydb > backup.sql
          </div>
          <h3>Restore Database</h3>
          <div class="code-block">
mysql -u username -p mydb < backup.sql
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>MySQL Tools</h2>
          <p>MySQL provides several tools for managing and interacting with your databases.</p>
          <ul>
            <li><strong>mysql:</strong> Command-line client for running SQL queries</li>
            <li><strong>mysqldump:</strong> Backup utility</li>
            <li><strong>phpMyAdmin:</strong> Web-based GUI for MySQL</li>
            <li><strong>MySQL Workbench:</strong> Visual database design and management</li>
            <li><strong>Percona Toolkit:</strong> Advanced database tools</li>
          </ul>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other MySQL Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Introduction to MySQL</button>
          <button class="topic-btn" data-index="1">Installation & Setup</button>
          <button class="topic-btn" data-index="2">Basic SQL Operations</button>
          <button class="topic-btn" data-index="3">Joins</button>
          <button class="topic-btn" data-index="4">Indexes</button>
          <button class="topic-btn" data-index="5">Backup & Restore</button>
          <button class="topic-btn" data-index="6">MySQL Tools</button>
        </div>
      </div>
    </div>
    <script>
    (function() {
        const sections = document.querySelectorAll('.lesson-section');
        let current = 0;
        const prevBtn = document.getElementById('prevLessonBtn');
        const nextBtn = document.getElementById('nextLessonBtn');
        const pageInfo = document.getElementById('lessonPageInfo');
        function showSection(idx) {
            sections.forEach((sec, i) => {
                sec.classList.toggle('active', i === idx);
            });
            prevBtn.disabled = idx === 0;
            nextBtn.disabled = idx === sections.length - 1;
            pageInfo.textContent = `Section ${idx + 1} of ${sections.length}`;
            current = idx;
        }
        prevBtn.onclick = function() {
            if (current > 0) {
                current--;
                showSection(current);
            }
        };
        nextBtn.onclick = function() {
            if (current < sections.length - 1) {
                current++;
                showSection(current);
            }
        };
        showSection(current);
        document.getElementById('otherTopicsBtn').onclick = function() {
            var dropdown = document.getElementById('otherTopicsDropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        };
        document.querySelectorAll('.topic-btn').forEach(function(btn) {
            btn.onclick = function() {
                var idx = parseInt(btn.getAttribute('data-index'));
                showSection(idx);
                document.getElementById('otherTopicsDropdown').style.display = 'none';
            };
        });
        document.addEventListener('click', function(e) {
            var dropdown = document.getElementById('otherTopicsDropdown');
            var btn = document.getElementById('otherTopicsBtn');
            if (!dropdown.contains(e.target) && e.target !== btn) {
                dropdown.style.display = 'none';
            }
        });
    })();
    </script>
</body>
</html>