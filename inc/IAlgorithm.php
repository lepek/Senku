<?php

/**
 * Algorithm Interface
 * 
 * We are preparing the design to be able
 * to use several algorithms, so we need to
 * know the public interface of all of them
 *
 */
interface IAlgorithm {
	public function solve();
	public function getSolution();
}