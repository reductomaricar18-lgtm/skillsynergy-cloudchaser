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
    <title>DynamoDB Intermediate - SkillSynergy</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #43cea2 0%, #185a9d 100%);
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
          <h2>Secondary Indexes (GSI & LSI)</h2>
          <a href="https://www.tutorialspoint.com/compilers/index.htm?fbclid=IwY2xjawLiH_JleHRuA2FlbQIxMABicmlkETFXd0s1bzVrVERRbGhuSTJJAR7dQUpWg7goO03kgpTTqQT5IJKmU9095iBCTky6NchAaPzQhtxC_CxoxCEY5g_aem_50f8taZNKCNBchBwwIDIWg" 
          style="color: #007bff; text-decoration: underline; font-weight: bold;">Click here for Onine Compiler</a>
          <p>Learn about Global Secondary Indexes (GSI) and Local Secondary Indexes (LSI) to enable flexible queries on non-primary key attributes.</p>
          <div class="code-block">
# Example: Creating a GSI in AWS CLI
aws dynamodb update-table \
  --table-name MyTable \
  --attribute-definitions AttributeName=Email,AttributeType=S \
  --global-secondary-index-updates '[{"Create":{"IndexName":"EmailIndex","KeySchema":[{"AttributeName":"Email","KeyType":"HASH"}],"Projection":{"ProjectionType":"ALL"},"ProvisionedThroughput":{"ReadCapacityUnits":5,"WriteCapacityUnits":5}}}]'
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Use GSIs for cross-partition queries and LSIs for alternate sort keys within the same partition.
          </div>
        </div>
        <div class="lesson-section" data-index="1">
          <h2>Batch Operations</h2>
          <p>BatchWriteItem and BatchGetItem allow you to read or write multiple items in a single request, improving efficiency.</p>
          <div class="code-block">
# Batch write example (Python boto3)
with table.batch_writer() as batch:
    batch.put_item(Item={"PK": "user#1", "Name": "Alice"})
    batch.put_item(Item={"PK": "user#2", "Name": "Bob"})
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Batch operations are limited to 25 items per request and 16 MB of data.
          </div>
        </div>
        <div class="lesson-section" data-index="2">
          <h2>Conditional Writes</h2>
          <p>Use ConditionExpression to ensure writes only happen if certain conditions are met, preventing overwrites and enforcing business rules.</p>
          <div class="code-block">
# Conditional put (Python boto3)
table.put_item(
    Item={"PK": "user#1", "score": 100},
    ConditionExpression="attribute_not_exists(PK)"
)
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Conditional writes help implement atomic counters, uniqueness, and optimistic locking.
          </div>
        </div>
        <div class="lesson-section" data-index="3">
          <h2>Querying and Scanning with Filters</h2>
          <p>Use FilterExpression to filter results after the initial query or scan, reducing the amount of data returned.</p>
          <div class="code-block">
# Query with filter (Python boto3)
response = table.query(
    KeyConditionExpression=Key('PK').eq('user#1'),
    FilterExpression=Attr('score').gt(50)
)
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Filters are applied after data is read, so they do not reduce read capacity usage.
          </div>
        </div>
        <div class="lesson-section" data-index="4">
          <h2>Pagination</h2>
          <p>DynamoDB paginates results for large queries and scans. Use LastEvaluatedKey to fetch the next page of results.</p>
          <div class="code-block">
# Paginated scan (Python boto3)
response = table.scan(Limit=10)
while 'LastEvaluatedKey' in response:
    response = table.scan(
        Limit=10,
        ExclusiveStartKey=response['LastEvaluatedKey']
    )
          </div>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Always check for LastEvaluatedKey to ensure you retrieve all results.
          </div>
        </div>
        <div class="lesson-section" data-index="5">
          <h2>Best Practices for Intermediate Use</h2>
          <ul>
            <li>Design indexes based on access patterns.</li>
            <li>Use batch operations for efficiency, but handle unprocessed items.</li>
            <li>Use conditional writes to prevent data loss.</li>
            <li>Paginate large queries and scans.</li>
            <li>Monitor and adjust provisioned throughput as needed.</li>
          </ul>
          <div class="tip-box">
            <strong>💡 Tip:</strong> Regularly review your table and index usage to optimize cost and performance.
          </div>
        </div>
      </div>
      <div class="lesson-nav" style="position:relative;">
        <button id="prevLessonBtn">Previous</button>
        <span id="lessonPageInfo"></span>
        <button id="nextLessonBtn">Next</button>
        <button id="otherTopicsBtn" style="margin-left: 24px; background: #6c63ff; position: relative; z-index: 11;">Other DynamoDB Topics</button>
        <div id="otherTopicsDropdown">
          <button class="topic-btn" data-index="0">Secondary Indexes (GSI & LSI)</button>
          <button class="topic-btn" data-index="1">Batch Operations</button>
          <button class="topic-btn" data-index="2">Conditional Writes</button>
          <button class="topic-btn" data-index="3">Querying and Scanning with Filters</button>
          <button class="topic-btn" data-index="4">Pagination</button>
          <button class="topic-btn" data-index="5">Best Practices for Intermediate Use</button>
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