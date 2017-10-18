<?php

/* layout.html.twig */
class __TwigTemplate_aac06daff98e197f72d1921ead7832df8209ebb445464010070cff5c9abc245f extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!doctype html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\"
          content=\"width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">
    <title>";
        // line 8
        echo twig_escape_filter($this->env, ($context["title"] ?? null), "html", null, true);
        echo "</title>
    <link rel=\"shortcut icon\" href=\"/favicon.ico\" />
\t";
        // line 10
        if (twig_constant("DEV")) {
            // line 11
            echo "\t\t";
            echo $this->getAttribute(($context["debugbar"] ?? null), "renderHead", array(), "method");
            echo "
\t";
        }
        // line 13
        echo "</head>
<body>

    ";
        // line 16
        $this->displayBlock('content', $context, $blocks);
        // line 17
        echo "
\t";
        // line 18
        if (twig_constant("DEV")) {
            // line 19
            echo "        ";
            echo $this->getAttribute(($context["debugbar"] ?? null), "render", array(), "method");
            echo "
    ";
        }
        // line 21
        echo "</body>
</html>
";
    }

    // line 16
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "layout.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  66 => 16,  60 => 21,  54 => 19,  52 => 18,  49 => 17,  47 => 16,  42 => 13,  36 => 11,  34 => 10,  29 => 8,  20 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("<!doctype html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <meta name=\"viewport\"
          content=\"width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"ie=edge\">
    <title>{{ title }}</title>
    <link rel=\"shortcut icon\" href=\"/favicon.ico\" />
\t{% if constant('DEV') %}
\t\t{{ debugbar.renderHead() | raw }}
\t{% endif %}
</head>
<body>

    {% block content %}{% endblock %}

\t{% if constant('DEV') %}
        {{ debugbar.render() | raw }}
    {% endif %}
</body>
</html>
", "layout.html.twig", "F:\\OpenServer\\OSPanel\\domains\\github\\Rudra-Framework\\app\\resources\\twig\\view\\layout.html.twig");
    }
}
