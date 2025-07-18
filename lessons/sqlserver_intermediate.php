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
    <title>SQL Server Intermediate - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #2980b9 0%, #6dd5fa 100%);
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
          <h2>Joins</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Combine rows from two or more tables using different types of joins.</p>
          <div class="code-block">
<span class="code-comment">-- INNER JOIN</span>
SELECT e.Name, d.DepartmentName
FROM Employees e
INNER JOIN Departments d ON e.DepartmentID = d.DepartmentID;

<span class="code-comment">-- LEFT JOIN</span>
SELECT e.Name, d.DepartmentName
FROM Employees e
LEFT JOIN Departments d ON e.DepartmentID = d.DepartmentID;

<span class="code-comment">-- RIGHT JOIN</span>
SELECT e.Name, d.DepartmentName
FROM Employees e
RIGHT JOIN Departments d ON e.DepartmentID = d.DepartmentID;

<span class="code-comment">-- FULL JOIN</span>
SELECT e.Name, d.DepartmentName
FROM Employees e
FULL JOIN Departments d ON e.DepartmentID = d.DepartmentID;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use INNER JOIN for matching rows, LEFT/RIGHT JOIN for unmatched rows, and FULL JOIN for all rows.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Subqueries</h2>
          <p>Use subqueries to nest one query inside another for more complex logic.</p>
          <div class="code-block">
<span class="code-comment">-- Subquery in SELECT</span>
SELECT Name, (SELECT COUNT(*) FROM Orders o WHERE o.EmployeeID = e.EmployeeID) AS OrderCount
FROM Employees e;

<span class="code-comment">-- Subquery in WHERE</span>
SELECT Name
FROM Employees
WHERE DepartmentID IN (SELECT DepartmentID FROM Departments WHERE Location = 'NY');
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Subqueries can be used in SELECT, FROM, and WHERE clauses.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Aggregation</h2>
          <p>Summarize data using GROUP BY and HAVING.</p>
          <div class="code-block">
<span class="code-comment">-- GROUP BY</span>
SELECT DepartmentID, COUNT(*) AS EmployeeCount
FROM Employees
GROUP BY DepartmentID;

<span class="code-comment">-- HAVING</span>
SELECT DepartmentID, COUNT(*) AS EmployeeCount
FROM Employees
GROUP BY DepartmentID
HAVING COUNT(*) > 5;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> HAVING filters groups after aggregation, WHERE filters rows before grouping.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Indexes</h2>
          <p>Improve query performance by creating indexes on columns.</p>
          <div class="code-block">
<span class="code-comment">-- Create an index</span>
CREATE INDEX idx_name ON Employees(Name);

<span class="code-comment">-- Drop an index</span>
DROP INDEX idx_name ON Employees;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Indexes speed up SELECTs but can slow down INSERT/UPDATE/DELETE.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Views</h2>
          <p>Views are virtual tables based on SQL queries.</p>
          <div class="code-block">
<span class="code-comment">-- Create a view</span>
CREATE VIEW EmployeeOrders AS
SELECT e.Name, o.OrderID
FROM Employees e
JOIN Orders o ON e.EmployeeID = o.EmployeeID;

<span class="code-comment">-- Use a view</span>
SELECT * FROM EmployeeOrders;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Views simplify complex queries and enhance security.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Transactions</h2>
          <p>Ensure data integrity using transactions.</p>
          <div class="code-block">
BEGIN TRANSACTION;
UPDATE Accounts SET Balance = Balance - 100 WHERE AccountID = 1;
UPDATE Accounts SET Balance = Balance + 100 WHERE AccountID = 2;
IF @@ERROR <> 0
    ROLLBACK TRANSACTION;
ELSE
    COMMIT TRANSACTION;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use transactions for multiple related changes to ensure atomicity.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Error Handling</h2>
          <p>Handle errors gracefully using TRY...CATCH blocks.</p>
          <div class="code-block">
BEGIN TRY
    -- SQL statements
    INSERT INTO Employees(Name) VALUES('John');
END TRY
BEGIN CATCH
    SELECT ERROR_MESSAGE() AS ErrorMessage;
END CATCH;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always handle errors to prevent data corruption and provide useful feedback.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other SQL Server Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Joins</button>
          <button class="topic-btn" data-index="1">Subqueries</button>
          <button class="topic-btn" data-index="2">Aggregation</button>
          <button class="topic-btn" data-index="3">Indexes</button>
          <button class="topic-btn" data-index="4">Views</button>
          <button class="topic-btn" data-index="5">Transactions</button>
          <button class="topic-btn" data-index="6">Error Handling</button>
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