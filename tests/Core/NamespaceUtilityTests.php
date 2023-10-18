<?php

namespace Alangiacomin\LaravelBasePack\Tests\Core;

use Alangiacomin\LaravelBasePack\Core\NamespaceUtility;
use Alangiacomin\LaravelBasePack\Tests\TestCase;

class NamespaceUtilityTests extends TestCase
{
    /****************/
    /* ELEMENT NAME */
    /****************/

    public function test_elementName_simple()
    {
        // Arrange

        // Act
        $name = NamespaceUtility::elementName('SimpleElement');

        // Assert
        expect($name)->toBe('SimpleElement');
    }

    public function test_elementName_complex()
    {
        // Arrange

        // Act
        $name = NamespaceUtility::elementName('Complex/Element');

        // Assert
        expect($name)->toBe('Element');
    }

    /*****************************/
    /* NORMALIZE NAMESPACE SLASH */
    /*****************************/

    public function test_normalizeNamespaceSlash_no_slash()
    {
        // Arrange

        // Act
        $name = NamespaceUtility::normalizeNamespaceSlash('SimpleElement');

        // Assert
        expect($name)->toBe('SimpleElement');
    }

    public function test_normalizeNamespaceSlash_slash()
    {
        // Arrange

        // Act
        $name = NamespaceUtility::normalizeNamespaceSlash('Simple/Element');

        // Assert
        expect($name)->toBe('Simple\Element');
    }

    public function test_normalizeNamespaceSlash_mixed()
    {
        // Arrange

        // Act
        $name = NamespaceUtility::normalizeNamespaceSlash('Simple\Mixed/Element');

        // Assert
        expect($name)->toBe('Simple\Mixed\Element');
    }

    /************************/
    /* NORMALIZE PATH SLASH */
    /************************/

    public function test_normalizePathSlash_no_slash()
    {
        // Arrange

        // Act
        $name = NamespaceUtility::normalizePathSlash('SimpleElement');

        // Assert
        expect($name)->toBe('SimpleElement');
    }

    public function test_normalizePathSlash_slash()
    {
        // Arrange

        // Act
        $name = NamespaceUtility::normalizePathSlash('Simple/Element');

        // Assert
        expect($name)->toBe('Simple/Element');
    }

    public function test_normalizePathSlash_mixed()
    {
        // Arrange

        // Act
        $name = NamespaceUtility::normalizePathSlash('Simple\Mixed/Element');

        // Assert
        expect($name)->toBe('Simple/Mixed/Element');
    }

    /************************/
    /* RELATIVE NAMESPACE */
    /************************/

    public function test_relativeNamespace_simple()
    {
        // Arrange

        // Act
        $name = NamespaceUtility::relativeNamespace('SimpleElement');

        // Assert
        expect($name)->toBe('');
    }

    public function test_relativeNamespace_sub()
    {
        // Arrange

        // Act
        $name = NamespaceUtility::relativeNamespace('Sub/Element');

        // Assert
        expect($name)->toBe('\Sub');
    }

    public function test_relativeNamespace_complex()
    {
        // Arrange

        // Act
        $name = NamespaceUtility::relativeNamespace('Complex/Sub/Element');

        // Assert
        expect($name)->toBe('\Complex\Sub');
    }
}
