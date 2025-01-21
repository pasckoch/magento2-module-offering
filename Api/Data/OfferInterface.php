<?php

namespace DnD\Offering\Api\Data;

interface OfferInterface
{
    /** @var string */
    public const OFFER_ID = 'offer_id';
    /** @var string */
    public const TITLE = 'title';
    /** @var string */
    public const LABEL = 'label';
    /** @var string */
    public const IMAGE_PATH = 'image_path';
    /** @var string */
    public const REDIRECT_URL = 'redirect_url';
    /** @var string */
    public const DATE_FROM = 'date_from';
    /** @var string */
    public const DATE_TO = 'date_to';

    /**
     * Getter id
     * @return ?int
     */
    public function getId(): ?int;

    /**
     * Getter offer title
     * @return string
     */
    public function getTitle(): string;

    /**
     * Setter offer title
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): OfferInterface;

    /**
     * Getter offer label
     * @return string
     */
    public function getLabel(): string;

    /**
     * Setter offer label
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): OfferInterface;

    /**
     * Getter offer image path
     * @return string|null
     */
    public function getImagePath(): ?string;

    /**
     * Setter offer image path
     * @param null|string $imagePath
     * @return $this
     */
    public function setImagePath(?string $imagePath): OfferInterface;

    /**
     * Getter offer redirect url
     * @return string
     */
    public function getRedirectUrl(): string;

    /**
     * Setter offer redirect url
     * @param string $redirectUrl
     * @return $this
     */
    public function setRedirectUrl(string $redirectUrl): OfferInterface;

    /**
     * Getter offer from date
     * @return ?string
     */
    public function getDateFrom(): ?string;

    /**
     * Setter offer from date
     * @param string $dateFrom
     * @return $this
     */
    public function setDateFrom(string $dateFrom): OfferInterface;

    /**
     * Getter offer to date
     * @return ?string
     */
    public function getDateTo(): ?string;

    /**
     * Setter offer to date
     * @param string $dateTo
     * @return $this
     */
    public function setDateTo(string $dateTo): OfferInterface;

}
