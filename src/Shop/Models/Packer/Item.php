<?php
/**
 * Box packing (3D bin packing, knapsack problem)
 * @package BoxPacker
 * @author Doug Wright
 */
  namespace Shop\Models\Packer;

  /**
   * An item to be packed
   * @author Doug Wright
   * @package BoxPacker
   */
  interface Item {

    /**
     * Item SKU etc
     * @return string
     */
    public function getDescription();

    /**
     * Item width in mm
     * @return int
     */
    public function getWidth($mm = true);

    /**
     * Item length in mm
     * @return int
     */
    public function getLength($mm = true);

    /**
     * Item depth in mm
     * @return int
     */
    public function getDepth($mm = true);

    /**
     * Item weight in g
     * @return int
     */
    public function getWeight($g = true);
    
    /**
     * Item volume in mm^3
     * @return int
     */
    public function getVolume();

  }
