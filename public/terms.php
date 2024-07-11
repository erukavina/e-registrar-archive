<?php
require_once '../../src/ClassLoader.php';
require_once("../../vendor/autoload.php");
$Document = new \Document\Document;
$Document->setTitle("Terms of Service");
$Document->setDescription("Review the terms of service for e-Registrar's platform. Understand the rules and regulations governing the use of our services.");
$Document->RenderHead();
$Document->RenderNavigation();

// Load Markdown content using Parsedown library
$markdownContent = file_get_contents('../../src/markdown_files/terms.md');
$Parsedown = new Parsedown();
$parsedContent = $Parsedown->text($markdownContent);
echo "<div class='container container-default'>" . $parsedContent . "</div>";
$Document->RenderFooter();
