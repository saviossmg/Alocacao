<?php
/**
 * VwCurso
 *
 * @Table(name="VwCurso", indexes={@Index(name="fk_vwCurso_unidade1_idx", columns={"Unidade"})})
 * @Entity
 */
class Vwcurso
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
     * @Column(name="Nome", type="string", length=45, nullable=false)
     */
    private $nome;

    /**
     * @var string
     *
     * @Column(name="Sigla", type="string", length=45, nullable=false)
     */
    private $sigla;

    /**
     * @var string
     *
     * @Column(name="CodCurso", type="string", length=45, nullable=false)
     */
    private $codcurso;

    /**
     * @var \Unidade
     *
     * @ManyToOne(targetEntity="Unidade")
     * @JoinColumns({
     *   @JoinColumn(name="Unidade", referencedColumnName="Id")
     * })
     */
    private $unidade;


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
     * @return Vwcurso
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
     * Set sigla
     *
     * @param string $sigla
     *
     * @return Vwcurso
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;

        return $this;
    }

    /**
     * Get sigla
     *
     * @return string
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * Set codcurso
     *
     * @param string $codcurso
     *
     * @return Vwcurso
     */
    public function setCodcurso($codcurso)
    {
        $this->codcurso = $codcurso;

        return $this;
    }

    /**
     * Get codcurso
     *
     * @return string
     */
    public function getCodcurso()
    {
        return $this->codcurso;
    }

    /**
     * Set unidade
     *
     * @param \Unidade $unidade
     *
     * @return Vwcurso
     */
    public function setUnidade(\Unidade $unidade = null)
    {
        $this->unidade = $unidade;

        return $this;
    }

    /**
     * Get unidade
     *
     * @return \Unidade
     */
    public function getUnidade()
    {
        return $this->unidade;
    }
}

