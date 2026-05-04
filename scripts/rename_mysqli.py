import os
import re

src_dir = 'extracted_project/SECLAB-main/project-code/src'

for root, dirs, files in os.walk(src_dir):
    for file in files:
        if file.endswith('.php') and file != 'config.php':
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                content = f.read()
            
            # Replace 'new mysqli' with 'new postgres_mysqli'
            new_content = re.sub(r'\bnew\s+mysqli\s*\(', 'new postgres_mysqli(', content)
            
            if new_content != content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(new_content)
                print(f"Updated {filepath}")
