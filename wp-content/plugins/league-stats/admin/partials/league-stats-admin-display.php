<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://hraza.de
 * @since      1.0.0
 *
 * @package    League_Stats
 * @subpackage League_Stats/admin/partials
 */

?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="container-2">
    <div id="page-wrapper">
        <div class="row" >
            <div class="col-lg-2 col-md-2 col-sm-6">
                <div class="circle-tile">
                    <a href="#">
                        <div class="circle-tile-heading dark-blue">
                            <i class="fa fa-users fa-fw fa-3x"></i>
                        </div>
                    </a>
                    <div class="circle-tile-content dark-blue">
                        <div class="circle-tile-description text-faded">
                            New Users
                        </div>
                        <div class="circle-tile-number text-faded">
                            <?php echo $this->wpb_user_count();?>
                            <span id="sparklineA"></span>
                        </div>
                        <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6">
                <div class="circle-tile">
                    <a href="#">
                        <div class="circle-tile-heading green">
                            <i class="fa fa-money fa-fw fa-3x"></i>
                        </div>
                    </a>
                    <div class="circle-tile-content green">
                        <div class="circle-tile-description text-faded">
                            Duels Answered
                        </div>
                        <div class="circle-tile-number text-faded">
                            <?php echo $this->league_duels_answered_count();?>
                        </div>
                        <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6">
                <div class="circle-tile">
                    <a href="#">
                        <div class="circle-tile-heading orange">
                            <i class="fa fa-bell fa-fw fa-3x"></i>
                        </div>
                    </a>
                    <div class="circle-tile-content orange">
                        <div class="circle-tile-description text-faded">
                            Total Duels
                        </div>
                        <div class="circle-tile-number text-faded">
                            <?php echo $this->league_duels_count();?>

                        </div>
                        <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6">
                <div class="circle-tile">
                    <a href="#">
                        <div class="circle-tile-heading blue">
                            <i class="fa fa-tasks fa-fw fa-3x"></i>
                        </div>
                    </a>
                    <div class="circle-tile-content blue">
                        <div class="circle-tile-description text-faded">
                            Tasks
                        </div>
                        <div class="circle-tile-number text-faded">
                            10
                            <span id="sparklineB"></span>
                        </div>
                        <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-6">
                <div class="circle-tile">
                    <a href="#">
                        <div class="circle-tile-heading red">
                            <i class="fa fa-shopping-cart fa-fw fa-3x"></i>
                        </div>
                    </a>
                    <div class="circle-tile-content red">
                        <div class="circle-tile-description text-faded">
                            Orders
                        </div>
                        <div class="circle-tile-number text-faded">
                            24
                            <span id="sparklineC"></span>
                        </div>
                        <a href="#" class="circle-tile-footer">More Info <i class="fa fa-chevron-circle-right"></i></a>
                    </div>
                </div>
            </div>
