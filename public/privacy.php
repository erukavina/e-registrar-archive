<?php
require_once '../src/ClassLoader.php';
require_once("../vendor/autoload.php");

$Document = new \Document\Document;
$Document->setTitle("Privacy Policy");
$Document->setDescription("Understand how e-Registrar safeguards your privacy. Learn about data collection, usage, and security measures.");
$Document->RenderHead();
$Document->RenderNavigation();

// Load Markdown content using Parsedown library
$markdownContent = file_get_contents('../src/markdown_files/privacy.md');
$Parsedown = new Parsedown();
$parsedContent = $Parsedown->text($markdownContent);
echo "<div class='container container-default'>".$parsedContent."</div>";
$Document->RenderFooter();
