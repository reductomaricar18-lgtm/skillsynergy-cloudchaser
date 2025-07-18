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
    <title>SQL Server Advanced - SkillSynergy</title>
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
          <h2>Stored Procedures</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Encapsulate SQL logic in reusable, parameterized routines.</p>
          <div class="code-block">
<span class="code-comment">-- Create a stored procedure</span>
CREATE PROCEDURE GetEmployeeByID @EmpID INT
AS
BEGIN
    SELECT * FROM Employees WHERE EmployeeID = @EmpID;
END;

<span class="code-comment">-- Execute a stored procedure</span>
EXEC GetEmployeeByID 1;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use stored procedures for security, performance, and code reuse.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Triggers</h2>
          <p>Automatically execute code in response to table events (INSERT, UPDATE, DELETE).</p>
          <div class="code-block">
<span class="code-comment">-- Create a trigger</span>
CREATE TRIGGER trg_AuditInsert
ON Employees
AFTER INSERT
AS
BEGIN
    INSERT INTO AuditLog(EmployeeID, Action)
    SELECT EmployeeID, 'INSERT' FROM inserted;
END;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use triggers for auditing, validation, and enforcing business rules.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>User-Defined Functions</h2>
          <p>Create custom scalar or table-valued functions for reusable logic.</p>
          <div class="code-block">
<span class="code-comment">-- Scalar function</span>
CREATE FUNCTION dbo.GetInitials(@Name NVARCHAR(100))
RETURNS NVARCHAR(10)
AS
BEGIN
    RETURN LEFT(@Name, 1) + '.';
END;

<span class="code-comment">-- Table-valued function</span>
CREATE FUNCTION dbo.GetEmployeesByDept(@DeptID INT)
RETURNS TABLE
AS
RETURN (
    SELECT * FROM Employees WHERE DepartmentID = @DeptID
);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Functions can be used in SELECT, WHERE, and JOIN clauses.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Advanced Indexing</h2>
          <p>Use filtered indexes and included columns for performance tuning.</p>
          <div class="code-block">
<span class="code-comment">-- Filtered index</span>
CREATE INDEX idx_active_employees ON Employees(Status)
WHERE Status = 'Active';

<span class="code-comment">-- Index with included columns</span>
CREATE INDEX idx_name_includes ON Employees(Name) INCLUDE (Email, Phone);
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Filtered and covering indexes can greatly improve query performance.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Query Optimization</h2>
          <p>Analyze and improve queries using execution plans and statistics.</p>
          <div class="code-block">
<span class="code-comment">-- Show execution plan</span>
SET SHOWPLAN_ALL ON;
GO
SELECT * FROM Employees WHERE Name = 'Alice';
GO
SET SHOWPLAN_ALL OFF;

<span class="code-comment">-- Update statistics</span>
UPDATE STATISTICS Employees;
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use execution plans to identify bottlenecks and optimize queries.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Security</h2>
          <p>Control access using roles and permissions.</p>
          <div class="code-block">
<span class="code-comment">-- Create a role</span>
CREATE ROLE SalesRole;
GRANT SELECT, INSERT ON Employees TO SalesRole;

<span class="code-comment">-- Add user to role</span>
EXEC sp_addrolemember 'SalesRole', 'jdoe';
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always follow the principle of least privilege for security.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Backup and Restore</h2>
          <p>Protect your data with regular backups and know how to restore them.</p>
          <div class="code-block">
<span class="code-comment">-- Backup database</span>
BACKUP DATABASE MyDB TO DISK = 'C:\backups\MyDB.bak';

<span class="code-comment">-- Restore database</span>
RESTORE DATABASE MyDB FROM DISK = 'C:\backups\MyDB.bak';
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Test your backups regularly to ensure you can restore when needed.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other SQL Server Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Stored Procedures</button>
          <button class="topic-btn" data-index="1">Triggers</button>
          <button class="topic-btn" data-index="2">User-Defined Functions</button>
          <button class="topic-btn" data-index="3">Advanced Indexing</button>
          <button class="topic-btn" data-index="4">Query Optimization</button>
          <button class="topic-btn" data-index="5">Security</button>
          <button class="topic-btn" data-index="6">Backup and Restore</button>
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