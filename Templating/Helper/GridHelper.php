<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\GridBundle\Templating\Helper;

use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\Definition\Filter;
use Sylius\Component\Grid\Renderer\GridRendererInterface;
use Sylius\Component\Grid\View\GridView;
use Symfony\Component\Templating\Helper\Helper;

/**
 * @author Paweł Jędrzejewski <pawel@sylius.org>
 */
class GridHelper extends Helper
{
    /**
     * @var GridRendererInterface
     */
    private $gridRenderer;

    /**
     * @param GridRendererInterface $gridRenderer
     */
    public function __construct(GridRendererInterface $gridRenderer)
    {
        $this->gridRenderer = $gridRenderer;
    }

    /**
     * @param GridView $gridView
     * @param string $template
     *
     * @return mixed
     */
    public function renderGrid(GridView $gridView, $template = null)
    {
        return $this->gridRenderer->render($gridView, $template);
    }

    /**
     * @param GridView $gridView
     * @param Field $field
     * @param mixed $data
     *
     * @return mixed
     */
    public function renderField(GridView $gridView, Field $field, $data)
    {
        return $this->gridRenderer->renderField($gridView, $field, $data);
    }

    /**
     * @param GridView $gridView
     * @param Action $action
     *
     * @return mixed
     */
    public function renderAction(GridView $gridView, Action $action, $data = null)
    {
        return $this->gridRenderer->renderAction($gridView, $action, $data);
    }

    /**
     * @param GridView $gridView
     * @param Filter $filter
     *
     * @return mixed
     */
    public function renderFilter(GridView $gridView, Filter $filter)
    {
        return $this->gridRenderer->renderFilter($gridView, $filter);
    }

    /**
     * @param string $path
     * @param GridView $gridView
     * @param Field $field
     */
    public function applySorting($path, GridView $gridView, Field $field)
    {
        $parameters = $gridView->getParameters();
        $definition = $gridView->getDefinition();
        $sortingPath = $field->getSortingPath();

        $criteria = $parameters->get('criteria', []);
        $sorting = $definition->getSorting();

        if ($parameters->has('sorting') ) {
            $currentSorting = $parameters->get('sorting');

            if (array_key_exists($sortingPath, $currentSorting)) {
                $sorting[$sortingPath] = $currentSorting[$sortingPath] === 'asc' ? 'desc' : 'asc';
            }
        }

        return $path.'?'.http_build_query(['sorting' => $sorting, 'criteria' => $criteria]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sylius_grid';
    }
}
