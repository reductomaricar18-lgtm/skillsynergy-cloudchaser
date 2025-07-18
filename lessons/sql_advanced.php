<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SQL Advanced - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #232526 0%, #414345 100%);
            min-height: 100vh;
        }
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
        .code-comment { color: #68d391; }
        .code-keyword { color: #f6ad55; }
        .code-string { color: #fbb6ce; }
        .code-number { color: #90cdf4; }
        .example-box { background: #e6fffa; border: 1px solid #81e6d9; border-radius: 8px; padding: 20px; margin: 15px 0; }
        .example-box h4 { color: #2c7a7b; margin-top: 0; }
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
          <h2>Transactions</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Transactions ensure a group of SQL statements are executed as a single unit, providing ACID properties.</p>
          <div class="code-block">
-- Start a transaction
START TRANSACTION;
UPDATE accounts SET balance = balance - 100 WHERE id = 1;
UPDATE accounts SET balance = balance + 100 WHERE id = 2;
COMMIT;

-- Rollback example
START TRANSACTION;
UPDATE accounts SET balance = balance - 100 WHERE id = 1;
-- Oops, something went wrong!
ROLLBACK;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use transactions to ensure data integrity in multi-step operations.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Stored Procedures & Functions</h2>
          <p>Stored procedures and functions encapsulate SQL logic for reuse and modularity.</p>
          <div class="code-block">
-- Stored Procedure
DELIMITER //
CREATE PROCEDURE AddEmployee(IN empName VARCHAR(50))
BEGIN
    INSERT INTO employees(name) VALUES(empName);
END //
DELIMITER ;

CALL AddEmployee('Alice');

-- Function
DELIMITER //
CREATE FUNCTION GetTotalEmployees() RETURNS INT
BEGIN
    DECLARE total INT;
    SELECT COUNT(*) INTO total FROM employees;
    RETURN total;
END //
DELIMITER ;

SELECT GetTotalEmployees();
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use procedures for actions, functions for calculations that return a value.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Triggers</h2>
          <p>Triggers are special procedures that run automatically in response to certain events on a table.</p>
          <div class="code-block">
-- After Insert Trigger
CREATE TRIGGER after_employee_insert
AFTER INSERT ON employees
FOR EACH ROW
INSERT INTO audit_log(action, emp_id) VALUES('insert', NEW.id);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Triggers are useful for auditing, enforcing rules, and synchronizing tables.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Window Functions</h2>
          <p>Window functions perform calculations across a set of table rows related to the current row.</p>
          <div class="code-block">
SELECT name, salary,
       RANK() OVER (ORDER BY salary DESC) as salary_rank,
       AVG(salary) OVER () as avg_salary
FROM employees;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Window functions are powerful for analytics and reporting.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Common Table Expressions (CTEs)</h2>
          <p>CTEs (WITH) make complex queries easier to read and maintain.</p>
          <div class="code-block">
WITH high_earners AS (
    SELECT name, salary FROM employees WHERE salary > 100000
)
SELECT * FROM high_earners;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> CTEs can be recursive for hierarchical data (e.g., org charts).
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Performance Tuning</h2>
          <ul>
            <li>Use EXPLAIN to analyze query plans.</li>
            <li>Optimize indexes for frequent queries.</li>
            <li>Avoid SELECT * in production queries.</li>
            <li>Partition large tables if needed.</li>
            <li>Monitor slow queries and optimize them.</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Regularly review and tune your database for best performance.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Security Best Practices</h2>
          <ul>
            <li>Use parameterized queries to prevent SQL injection.</li>
            <li>Limit user privileges to only what's necessary.</li>
            <li>Encrypt sensitive data at rest and in transit.</li>
            <li>Regularly back up your database.</li>
            <li>Audit and monitor database access.</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Security is critical—never trust user input and always follow best practices.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other SQL Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Transactions</button>
          <button class="topic-btn" data-index="1">Stored Procedures & Functions</button>
          <button class="topic-btn" data-index="2">Triggers</button>
          <button class="topic-btn" data-index="3">Window Functions</button>
          <button class="topic-btn" data-index="4">CTEs (WITH)</button>
          <button class="topic-btn" data-index="5">Performance Tuning</button>
          <button class="topic-btn" data-index="6">Security Best Practices</button>
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