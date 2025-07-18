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
    <title>DynamoDB Advanced - SkillSynergy</title>
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
          <p>Use DynamoDB transactions to perform multiple operations atomically, ensuring all succeed or none are applied.</p>
          <div class="code-block">
# Transaction write (Python boto3)
dynamodb.transact_write_items(
    TransactItems=[
        {
            'Put': {
                'TableName': 'MyTable',
                'Item': {'PK': {'S': 'user#1'}, 'score': {'N': '100'}}
            }
        },
        {
            'Update': {
                'TableName': 'MyTable',
                'Key': {'PK': {'S': 'user#2'}},
                'UpdateExpression': 'SET score = score + :inc',
                'ExpressionAttributeValues': {':inc': {'N': '10'}}
            }
        }
    ]
)
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Transactions are limited to 25 items or 4 MB of data per request.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Streams</h2>
          <p>DynamoDB Streams capture table activity for real-time processing, triggers, and data replication.</p>
          <div class="code-block">
# Enable stream (AWS CLI)
aws dynamodb update-table --table-name MyTable --stream-specification StreamEnabled=true,StreamViewType=NEW_AND_OLD_IMAGES
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use Lambda to process stream records for event-driven architectures.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Time to Live (TTL)</h2>
          <p>TTL automatically deletes expired items, helping manage storage and costs.</p>
          <div class="code-block">
# Enable TTL (AWS CLI)
aws dynamodb update-time-to-live --table-name MyTable --time-to-live-specification "Enabled=true, AttributeName=expireAt"
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use TTL for session data, temporary records, and automatic cleanup.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>On-Demand vs Provisioned Capacity</h2>
          <p>Choose between on-demand (pay-per-request) and provisioned (fixed throughput) capacity modes for cost and performance optimization.</p>
          <div class="code-block">
# Switch to on-demand (AWS CLI)
aws dynamodb update-table --table-name MyTable --billing-mode PAY_PER_REQUEST
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> On-demand is great for unpredictable workloads; provisioned is cost-effective for steady traffic.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Advanced Data Modeling (Single-Table Design)</h2>
          <p>Store multiple entity types in a single table using composite keys and type attributes for efficient access patterns.</p>
          <div class="code-block">
# Example item
{"PK": "USER#1", "SK": "PROFILE#1", "Type": "User", "Name": "Alice"}
{"PK": "USER#1", "SK": "ORDER#100", "Type": "Order", "OrderDate": "2024-06-01"}
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Single-table design enables complex queries and relationships with fewer tables.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Security (IAM, Encryption)</h2>
          <ul>
            <li>Use IAM policies to control access to tables and indexes.</li>
            <li>Enable server-side encryption (SSE) for data at rest.</li>
            <li>Use VPC endpoints for private connectivity.</li>
          </ul>
          <div class="code-block">
# Enable SSE (AWS CLI)
aws dynamodb update-table --table-name MyTable --sse-specification Enabled=true
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Follow the principle of least privilege and audit access regularly.
          </div>
        </div>
        <div class="lesson-section" data-index="6">
          <h2>Performance Optimization</h2>
          <ul>
            <li>Use GSIs and LSIs for efficient queries.</li>
            <li>Project only needed attributes to reduce read costs.</li>
            <li>Monitor with CloudWatch and enable Auto Scaling.</li>
            <li>Use parallel scans for large tables.</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Regularly review CloudWatch metrics and adjust capacity or indexes as needed.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other DynamoDB Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Transactions</button>
          <button class="topic-btn" data-index="1">Streams</button>
          <button class="topic-btn" data-index="2">Time to Live (TTL)</button>
          <button class="topic-btn" data-index="3">On-Demand vs Provisioned Capacity</button>
          <button class="topic-btn" data-index="4">Advanced Data Modeling</button>
          <button class="topic-btn" data-index="5">Security (IAM, Encryption)</button>
          <button class="topic-btn" data-index="6">Performance Optimization</button>
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