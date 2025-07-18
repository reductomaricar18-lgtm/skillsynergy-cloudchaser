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
    <title>MySQL Advanced - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #232526 0%, #6dd5fa 100%);
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
          <h2>Views</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Views are virtual tables based on the result-set of a SQL statement.</p>
          <div class="code-block">
<span class="code-comment">-- Creating a view</span>
CREATE VIEW sales_view AS
SELECT name, salary FROM employees WHERE dept_id = 2;

<span class="code-comment">-- Using a view</span>
SELECT * FROM sales_view;

<span class="code-comment">-- Dropping a view</span>
DROP VIEW sales_view;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Views simplify complex queries and enhance security by restricting access.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Stored Procedures & Functions</h2>
          <p>Stored procedures and functions are reusable SQL code blocks stored in the database.</p>
          <div class="code-block">
<span class="code-comment">-- Stored procedure</span>
DELIMITER //
CREATE PROCEDURE GiveRaise(IN emp_id INT, IN amount DECIMAL(10,2))
BEGIN
  UPDATE employees SET salary = salary + amount WHERE id = emp_id;
END //
DELIMITER ;

CALL GiveRaise(1, 500.00);

<span class="code-comment">-- Stored function</span>
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
            <strong>💡 Tip:</strong> Use procedures for actions, functions for returning values.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Triggers</h2>
          <p>Triggers are SQL code that automatically execute in response to certain events on a table.</p>
          <div class="code-block">
<span class="code-comment">-- Trigger example</span>
DELIMITER //
CREATE TRIGGER before_employee_update
BEFORE UPDATE ON employees
FOR EACH ROW
BEGIN
  INSERT INTO audit_log(emp_id, old_salary, new_salary)
  VALUES (OLD.id, OLD.salary, NEW.salary);
END //
DELIMITER ;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Triggers are useful for auditing and enforcing business rules.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Advanced Transactions</h2>
          <p>Control transaction isolation levels and locks for data consistency and concurrency.</p>
          <div class="code-block">
<span class="code-comment">-- Isolation levels</span>
SET SESSION TRANSACTION ISOLATION LEVEL SERIALIZABLE;
START TRANSACTION;
-- your queries here
COMMIT;

<span class="code-comment">-- Explicit locks</span>
LOCK TABLES employees WRITE;
-- your queries here
UNLOCK TABLES;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use the right isolation level for your application's needs.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Performance Tuning</h2>
          <p>Analyze and optimize queries for better performance.</p>
          <div class="code-block">
<span class="code-comment">-- EXPLAIN for query analysis</span>
EXPLAIN SELECT * FROM employees WHERE dept_id = 2;

<span class="code-comment">-- Optimizing queries</span>
SELECT name FROM employees WHERE dept_id = 2 AND salary > 50000 ORDER BY salary DESC LIMIT 10;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use EXPLAIN to understand and improve query execution plans.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Security</h2>
          <p>Manage users, privileges, and protect against SQL injection.</p>
          <div class="code-block">
<span class="code-comment">-- Creating a user</span>
CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';

<span class="code-comment">-- Granting privileges</span>
GRANT SELECT, INSERT ON mydb.* TO 'newuser'@'localhost';

<span class="code-comment">-- Preventing SQL injection (use prepared statements in application code)</span>
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always use least privilege and prepared statements for security.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other MySQL Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Views</button>
          <button class="topic-btn" data-index="1">Stored Procedures & Functions</button>
          <button class="topic-btn" data-index="2">Triggers</button>
          <button class="topic-btn" data-index="3">Advanced Transactions</button>
          <button class="topic-btn" data-index="4">Performance Tuning</button>
          <button class="topic-btn" data-index="5">Security</button>
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