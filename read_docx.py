import zipfile
import xml.etree.ElementTree as ET
import os
import sys

def extract_text(actual_path):
    try:
        with zipfile.ZipFile(actual_path) as docx:
            tree = ET.XML(docx.read('word/document.xml'))
            namespaces = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
            text = []
            for paragraph in tree.iterfind('.//w:p', namespaces):
                texts = [node.text for node in paragraph.iterfind('.//w:t', namespaces) if node.text]
                if texts:
                    text.append(''.join(texts))
                else:
                    text.append('')
            return '\n'.join(text)
    except Exception as e:
        return str(e)

files = [f for f in os.listdir('.') if f.endswith('.docx')]
for f in files:
    print(f"--- File: {f} ---")
    print(extract_text(f))
    print("\n")
