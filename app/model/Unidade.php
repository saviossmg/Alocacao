<?php
/**
 * Unidade
 *
 * @Table(name="Unidade", indexes={@Index(name="fk_unidade_vwServidores1_idx", columns={"DiretorGeral"}), @Index(name="fk_unidade_vwServidores2_idx", columns={"Administrador"})})
 * @Entity
 */
class Unidade
{
    /**
     * @var integer
     *
     * @Column(name="Id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="Nome", type="string", length=50, nullable=false)
     */
    private $nome;

    /**
     * @var string
     *
     * @Column(name="Endereco", type="string", length=100, nullable=false)
     */
    private $endereco;

    /**
     * @var integer
     *
     * @Column(name="Cep", type="integer", nullable=false)
     */
    private $cep;

    /**
     * @var float
     *
     * @Column(name="Latitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $latitude;

    /**
     * @var float
     *
     * @Column(name="Longitude", type="float", precision=10, scale=0, nullable=true)
     */
    private $longitude;

    /**
     * @var boolean
     *
     * @Column(name="Ativo", type="boolean", nullable=false)
     */
    private $ativo;

    /**
     * @var \Vwservidor
     *
     * @ManyToOne(targetEntity="Vwservidor")
     * @JoinColumns({
     *   @JoinColumn(name="DiretorGeral", referencedColumnName="Id")
     * })
     */
    private $diretorgeral;

    /**
     * @var \Vwservidor
     *
     * @ManyToOne(targetEntity="Vwservidor")
     * @JoinColumns({
     *   @JoinColumn(name="Administrador", referencedColumnName="Id")
     * })
     */
    private $administrador;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nome
     *
     * @param string $nome
     *
     * @return Unidade
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set endereco
     *
     * @param string $endereco
     *
     * @return Unidade
     */
    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;

        return $this;
    }

    /**
     * Get endereco
     *
     * @return string
     */
    public function getEndereco()
    {
        return $this->endereco;
    }

    /**
     * Set cep
     *
     * @param integer $cep
     *
     * @return Unidade
     */
    public function setCep($cep)
    {
        $this->cep = $cep;

        return $this;
    }

    /**
     * Get cep
     *
     * @return integer
     */
    public function getCep()
    {
        return $this->cep;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Unidade
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Unidade
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set ativo
     *
     * @param boolean $ativo
     *
     * @return Unidade
     */
    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;

        return $this;
    }

    /**
     * Get ativo
     *
     * @return boolean
     */
    public function getAtivo()
    {
        return $this->ativo;
    }

    /**
     * Set diretorgeral
     *
     * @param \Vwservidor $diretorgeral
     *
     * @return Unidade
     */
    public function setDiretorgeral(\Vwservidor $diretorgeral = null)
    {
        $this->diretorgeral = $diretorgeral;

        return $this;
    }

    /**
     * Get diretorgeral
     *
     * @return \Vwservidor
     */
    public function getDiretorgeral()
    {
        return $this->diretorgeral;
    }

    /**
     * Set administrador
     *
     * @param \Vwservidor $administrador
     *
     * @return Unidade
     */
    public function setAdministrador(\Vwservidor $administrador = null)
    {
        $this->administrador = $administrador;

        return $this;
    }

    /**
     * Get administrador
     *
     * @return \Vwservidor
     */
    public function getAdministrador()
    {
        return $this->administrador;
    }
}

